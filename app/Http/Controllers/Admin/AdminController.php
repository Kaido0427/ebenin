<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\organization as Organization;
use App\Models\OrganizationSubscription;
use App\Models\post as Post;
use App\Models\transaction as Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $currentMonthStart = now()->copy()->startOfMonth();
        $currentMonthEnd = now()->copy()->endOfMonth();
        $previousMonthStart = now()->copy()->subMonth()->startOfMonth();
        $previousMonthEnd = now()->copy()->subMonth()->endOfMonth();

        $kpis = [
            'users_total' => User::count(),
            'users_active' => User::where('is_active', true)->count(),
            'blogs_total' => Organization::count(),
            'blogs_active' => Organization::where('is_active', true)->count(),
            'posts_total' => Post::count(),
            'posts_hidden' => Post::whereIn('editorial_status', ['hidden', 'rejected'])->count(),
            'payments_total' => Transaction::count(),
            'payments_revenue' => Transaction::where('status', 'paid')->sum('amount'),
            'expiring_soon' => OrganizationSubscription::whereNotNull('expires_at')
                ->whereBetween('expires_at', [now(), now()->copy()->addDays(7)])
                ->count(),
        ];

        $kpiChanges = [
            'users_total' => $this->buildDeltaMeta(
                User::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count(),
                User::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count()
            ),
            'blogs_total' => $this->buildDeltaMeta(
                Organization::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count(),
                Organization::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count()
            ),
            'posts_total' => $this->buildDeltaMeta(
                Post::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->count(),
                Post::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count()
            ),
            'payments_revenue' => $this->buildDeltaMeta(
                (float) Transaction::where('status', 'paid')
                    ->whereBetween(DB::raw('COALESCE(paid_at, created_at)'), [$currentMonthStart, $currentMonthEnd])
                    ->sum('amount'),
                (float) Transaction::where('status', 'paid')
                    ->whereBetween(DB::raw('COALESCE(paid_at, created_at)'), [$previousMonthStart, $previousMonthEnd])
                    ->sum('amount')
            ),
        ];

        $monthlyRevenue = collect(range(5, 0))->map(function ($offset) {
            $date = now()->copy()->startOfMonth()->subMonths($offset);

            return [
                'label' => $date->format('M'),
                'value' => (float) Transaction::where('status', 'paid')
                    ->whereYear('paid_at', $date->year)
                    ->whereMonth('paid_at', $date->month)
                    ->sum('amount'),
            ];
        })->push([
            'label' => now()->format('M'),
            'value' => (float) Transaction::where('status', 'paid')
                ->whereYear('paid_at', now()->year)
                ->whereMonth('paid_at', now()->month)
                ->sum('amount'),
        ]);

        $monthlyPosts = collect(range(5, 0))->map(function ($offset) {
            $date = now()->copy()->startOfMonth()->subMonths($offset);

            return [
                'label' => $date->format('M'),
                'value' => Post::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        })->push([
            'label' => now()->format('M'),
            'value' => Post::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count(),
        ]);

        $heroInsights = [
            [
                'label' => 'Utilisateurs actifs',
                'value' => number_format($kpis['users_active']),
                'meta' => number_format($kpis['users_total']) . ' comptes au total',
            ],
            [
                'label' => 'Blogs visibles',
                'value' => number_format($kpis['blogs_active']),
                'meta' => number_format($kpis['expiring_soon']) . ' echeances a traiter rapidement',
            ],
            [
                'label' => 'Posts a moderer',
                'value' => number_format($kpis['posts_hidden']),
                'meta' => 'Contenus caches ou rejetes',
            ],
        ];

        $expiringSubscriptions = OrganizationSubscription::with('organization')
            ->whereNotNull('expires_at')
            ->orderBy('expires_at')
            ->take(8)
            ->get();

        $flaggedPosts = Post::with('user.organization')
            ->whereIn('editorial_status', ['hidden', 'rejected'])
            ->latest()
            ->take(6)
            ->get();

        $latestTransactions = Transaction::with('organization', 'admin')
            ->latest(DB::raw('COALESCE(paid_at, created_at)'))
            ->take(8)
            ->get();

        $latestPosts = Post::with('user.organization', 'rubriques')
            ->latest()
            ->take(8)
            ->get();

        $activityFeed = $this->buildDashboardActivityFeed($latestTransactions, $latestPosts, $expiringSubscriptions);

        return view('admin.dashboard', compact(
            'kpis',
            'kpiChanges',
            'monthlyRevenue',
            'monthlyPosts',
            'heroInsights',
            'expiringSubscriptions',
            'flaggedPosts',
            'latestTransactions',
            'latestPosts',
            'activityFeed'
        ));
    }

    public function users(Request $request)
    {
        $users = User::with('organization')
            ->withCount('posts')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery
                        ->where('name', 'like', '%' . $request->q . '%')
                        ->orWhere('email', 'like', '%' . $request->q . '%')
                        ->orWhere('phone', 'like', '%' . $request->q . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status === 'active');
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $userStats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'with_blog' => User::whereNotNull('organization_id')->count(),
        ];

        return view('admin.users', compact('users', 'userStats'));
    }

    public function toggleUserStatus(Request $request, User $user)
    {
        $request->validate([
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $nextState = !$user->is_active;
        $user->forceFill([
            'is_active' => $nextState,
            'deactivated_at' => $nextState ? null : now(),
            'deactivation_reason' => $nextState ? null : $request->input('reason'),
        ])->save();

        if ($user->organization) {
            $user->organization->forceFill([
                'is_active' => $nextState,
                'is_publicly_visible' => $nextState,
                'deactivated_at' => $nextState ? null : now(),
                'deactivation_reason' => $nextState ? null : ($request->input('reason') ?: 'Suspendu depuis la fiche utilisateur'),
            ])->save();
        }

        return back()->with('success', $nextState ? 'Utilisateur reactive.' : 'Utilisateur suspendu.');
    }

    public function blogs(Request $request)
    {
        $blogs = Organization::with(['subscription', 'ownerUsers'])
            ->withCount(['ownerUsers', 'transactions'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery
                        ->where('organization_name', 'like', '%' . $request->q . '%')
                        ->orWhere('organization_email', 'like', '%' . $request->q . '%')
                        ->orWhere('subdomain', 'like', '%' . $request->q . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status === 'active');
            })
            ->orderBy('organization_name')
            ->paginate(15)
            ->withQueryString();

        $blogStats = [
            'total' => Organization::count(),
            'active' => Organization::where('is_active', true)->count(),
            'public' => Organization::where('is_publicly_visible', true)->count(),
            'expiring' => OrganizationSubscription::whereNotNull('expires_at')
                ->whereBetween('expires_at', [now(), now()->copy()->addDays(7)])
                ->count(),
        ];

        return view('admin.blogs', compact('blogs', 'blogStats'));
    }

    public function toggleBlogStatus(Request $request, Organization $organization)
    {
        $request->validate([
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $nextState = !$organization->is_active;
        $organization->forceFill([
            'is_active' => $nextState,
            'is_publicly_visible' => $nextState,
            'deactivated_at' => $nextState ? null : now(),
            'deactivation_reason' => $nextState ? null : $request->input('reason'),
        ])->save();

        User::where('organization_id', $organization->id)->update([
            'is_active' => $nextState,
            'deactivated_at' => $nextState ? null : now(),
            'deactivation_reason' => $nextState ? null : ($request->input('reason') ?: 'Synchronise avec le blog'),
        ]);

        return back()->with('success', $nextState ? 'Blog reactive.' : 'Blog suspendu et masque publiquement.');
    }

    public function toggleBlogVisibility(Organization $organization)
    {
        $organization->forceFill([
            'is_publicly_visible' => !$organization->is_publicly_visible,
        ])->save();

        return back()->with('success', $organization->is_publicly_visible ? 'Blog rendu public.' : 'Blog masque du public.');
    }

    public function posts(Request $request)
    {
        $posts = Post::with(['user.organization', 'rubriques'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('libelle', 'like', '%' . $request->q . '%');
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('editorial_status', $request->status);
            })
            ->when($request->filled('organization_id'), function ($query) use ($request) {
                $query->whereHas('user', fn($subQuery) => $subQuery->where('organization_id', $request->organization_id));
            })
            ->when($request->filled('featured'), function ($query) use ($request) {
                $query->where('featured', $request->featured === '1');
            })
            ->when($request->filled('breaking'), function ($query) use ($request) {
                $query->where('is_breaking', $request->breaking === '1');
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $organizations = Organization::orderBy('organization_name')->get(['id', 'organization_name']);
        $postStats = [
            'total' => Post::count(),
            'published' => Post::where('editorial_status', 'published')->count(),
            'hidden' => Post::where('editorial_status', 'hidden')->count(),
            'breaking' => Post::where('is_breaking', true)->count(),
        ];

        return view('admin.posts', compact('posts', 'organizations', 'postStats'));
    }

    public function updatePostEditorial(Request $request, Post $post)
    {
        $validated = $request->validate([
            'editorial_status' => ['required', Rule::in(['published', 'hidden', 'rejected'])],
            'editorial_note' => ['nullable', 'string', 'max:1000'],
            'featured' => ['nullable', 'boolean'],
            'is_breaking' => ['nullable', 'boolean'],
        ]);

        $post->update([
            'editorial_status' => $validated['editorial_status'],
            'editorial_note' => $validated['editorial_note'] ?? null,
            'featured' => (int) ($validated['featured'] ?? false),
            'is_breaking' => (bool) ($validated['is_breaking'] ?? false),
        ]);

        return back()->with('success', 'Regles editoriales mises a jour.');
    }

    public function payments(Request $request)
    {
        $transactions = Transaction::with(['organization', 'admin'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->q . '%';

                $query->where(function ($subQuery) use ($term) {
                    $subQuery
                        ->where('reference', 'like', $term)
                        ->orWhere('token', 'like', $term)
                        ->orWhere('payment_method', 'like', $term)
                        ->orWhereHas('organization', function ($organizationQuery) use ($term) {
                            $organizationQuery
                                ->where('organization_name', 'like', $term)
                                ->orWhere('organization_email', 'like', $term)
                                ->orWhere('subdomain', 'like', $term);
                        });
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('source'), function ($query) use ($request) {
                $query->where('source', $request->source);
            })
            ->when($request->filled('organization_id'), function ($query) use ($request) {
                $query->where('organization_id', $request->organization_id);
            })
            ->latest(DB::raw('COALESCE(paid_at, created_at)'))
            ->paginate(20)
            ->withQueryString();

        $organizations = Organization::orderBy('organization_name')->get(['id', 'organization_name', 'organization_phone']);
        $paymentStats = [
            'transactions_total' => Transaction::count(),
            'paid_total' => Transaction::where('status', 'paid')->sum('amount'),
            'manual_total' => Transaction::where('status', 'paid')->where('source', 'manual')->sum('amount'),
            'auto_total' => Transaction::where('status', 'paid')->where('source', 'kkiapay')->sum('amount'),
            'pending_total' => Transaction::where('status', 'pending')->count(),
            'failed_total' => Transaction::where('status', 'failed')->count(),
        ];
        $recentTransactions = Transaction::with(['organization', 'admin'])
            ->latest(DB::raw('COALESCE(paid_at, created_at)'))
            ->take(6)
            ->get();
        $subscriptionHealth = [
            'active' => OrganizationSubscription::where('status', 'active')
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>=', now());
                })
                ->count(),
            'expiring' => OrganizationSubscription::whereNotNull('expires_at')
                ->whereBetween('expires_at', [now(), now()->copy()->addDays(7)])
                ->count(),
            'expired' => OrganizationSubscription::whereNotNull('expires_at')
                ->where('expires_at', '<', now())
                ->count(),
        ];

        return view('admin.payments', compact('transactions', 'organizations', 'paymentStats', 'recentTransactions', 'subscriptionHealth'));
    }

    public function storeManualPayment(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'exists:organizations,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'months_awarded' => ['required', 'integer', 'min:1', 'max:24'],
            'status' => ['required', Rule::in(['paid', 'pending', 'failed'])],
            'payment_method' => ['required', 'string', 'max:100'],
            'reference' => ['nullable', 'string', 'max:191'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $organization = Organization::findOrFail($validated['organization_id']);

        Transaction::create([
            'phone' => $organization->organization_phone ?? 'N/A',
            'amount' => $validated['amount'],
            'status' => $validated['status'],
            'token' => $validated['reference'] ?: 'manual-' . now()->timestamp,
            'payment_method' => $validated['payment_method'],
            'organization_id' => $organization->id,
            'source' => 'manual',
            'reference' => $validated['reference'] ?? null,
            'paid_at' => $validated['status'] === 'paid' ? now() : null,
            'months_awarded' => $validated['months_awarded'],
            'admin_id' => auth('admin')->id(),
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($validated['status'] === 'paid') {
            $subscription = $this->applySubscriptionRenewal($organization, $validated['months_awarded'], $validated['notes'] ?? null);
            $this->syncLegacyUsersFromSubscription($organization, $subscription);
        }

        return back()->with('success', 'Paiement manuel enregistre.');
    }

    public function subscriptions(Request $request)
    {
        $subscriptions = OrganizationSubscription::with('organization', 'manager')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->q . '%';

                $query->where(function ($subQuery) use ($term) {
                    $subQuery
                        ->where('plan_name', 'like', $term)
                        ->orWhereHas('organization', function ($organizationQuery) use ($term) {
                            $organizationQuery
                                ->where('organization_name', 'like', $term)
                                ->orWhere('organization_email', 'like', $term)
                                ->orWhere('subdomain', 'like', $term);
                        });
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('expires_at')
            ->paginate(20)
            ->withQueryString();

        $subscriptionStats = [
            'total' => OrganizationSubscription::count(),
            'active' => OrganizationSubscription::where('status', 'active')
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>=', now());
                })
                ->count(),
            'expiring' => OrganizationSubscription::whereNotNull('expires_at')
                ->whereBetween('expires_at', [now(), now()->copy()->addDays(7)])
                ->count(),
            'expired' => OrganizationSubscription::whereNotNull('expires_at')
                ->where('expires_at', '<', now())
                ->count(),
        ];

        return view('admin.subscriptions', compact('subscriptions', 'subscriptionStats'));
    }

    public function renewSubscription(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'months_awarded' => ['required', 'integer', 'min:1', 'max:24'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $subscription = $this->applySubscriptionRenewal($organization, $validated['months_awarded'], $validated['notes'] ?? null);
        $this->syncLegacyUsersFromSubscription($organization, $subscription);

        Transaction::create([
            'phone' => $organization->organization_phone ?? 'N/A',
            'amount' => (float) ($validated['amount'] ?? 0),
            'status' => 'paid',
            'token' => 'admin-renewal-' . now()->timestamp,
            'payment_method' => 'manual',
            'organization_id' => $organization->id,
            'source' => 'manual',
            'reference' => 'renewal-' . $organization->id . '-' . now()->format('YmdHis'),
            'paid_at' => now(),
            'months_awarded' => $validated['months_awarded'],
            'admin_id' => auth('admin')->id(),
            'notes' => $validated['notes'] ?? 'Renouvellement back-office',
        ]);

        return back()->with('success', 'Abonnement prolonge.');
    }

    public function admins(Request $request)
    {
        $admins = Admin::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->q . '%';

                $query->where(function ($subQuery) use ($term) {
                    $subQuery
                        ->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('role', 'like', $term);
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role', $request->role);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status === 'active');
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $adminStats = [
            'total' => Admin::count(),
            'active' => Admin::where('is_active', true)->count(),
            'super_admins' => Admin::where('role', 'super_admin')->count(),
            'connected_recently' => Admin::where('last_login_at', '>=', now()->copy()->subDays(7))->count(),
        ];

        return view('admin.admins', compact('admins', 'adminStats'));
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191', 'unique:admins,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['super_admin', 'editorial_admin', 'billing_support'])],
        ]);

        Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'is_active' => true,
            'preferred_theme' => 'light',
        ]);

        return back()->with('success', 'Compte admin cree.');
    }

    public function toggleAdminStatus(Admin $admin)
    {
        if ($admin->id === auth('admin')->id()) {
            return back()->with('error', 'Tu ne peux pas desactiver ton propre compte.');
        }

        $admin->update([
            'is_active' => !$admin->is_active,
        ]);

        return back()->with('success', $admin->is_active ? 'Admin reactive.' : 'Admin desactive.');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191', Rule::unique('admins', 'email')->ignore($admin->id)],
            'preferred_theme' => ['required', Rule::in(['light', 'dark'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'preferred_theme' => $validated['preferred_theme'],
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        $admin->update($payload);

        return back()->with('success', 'Profil admin mis a jour.');
    }

    private function applySubscriptionRenewal(Organization $organization, int $monthsAwarded, ?string $notes = null): OrganizationSubscription
    {
        $subscription = OrganizationSubscription::firstOrNew([
            'organization_id' => $organization->id,
        ]);

        $baseDate = $subscription->expires_at && now()->lessThan($subscription->expires_at)
            ? $subscription->expires_at->copy()
            : now();

        if (!$subscription->started_at) {
            $subscription->started_at = now();
        }

        $subscription->plan_name = $subscription->plan_name ?: 'Blog Standard';
        $subscription->status = 'active';
        $subscription->renewal_cycle_months = 1;
        $subscription->is_auto_renew = false;
        $subscription->last_payment_at = now();
        $subscription->managed_by_admin_id = auth('admin')->id();
        $subscription->notes = $notes;
        $subscription->expires_at = $baseDate->copy()->addMonths($monthsAwarded);
        $subscription->next_renewal_at = $subscription->expires_at;
        $subscription->save();

        return $subscription->fresh();
    }

    private function syncLegacyUsersFromSubscription(Organization $organization, OrganizationSubscription $subscription): void
    {
        $months = $subscription->started_at && $subscription->expires_at
            ? max(1, $subscription->started_at->diffInMonths($subscription->expires_at))
            : 0;

        User::where('organization_id', $organization->id)->update([
            'subscription_started_at' => $subscription->started_at,
            'subscription_quantity' => $months,
            'is_active' => $organization->is_active,
        ]);
    }

    private function buildDeltaMeta(int|float $current, int|float $previous): array
    {
        $difference = $current - $previous;
        $percent = $previous > 0
            ? round(($difference / $previous) * 100)
            : ($current > 0 ? 100 : 0);

        return [
            'direction' => $difference >= 0 ? 'up' : 'down',
            'percent' => abs((int) $percent),
            'difference' => $difference,
            'current' => $current,
            'previous' => $previous,
        ];
    }

    private function buildDashboardActivityFeed($latestTransactions, $latestPosts, $expiringSubscriptions)
    {
        $paymentFeed = $latestTransactions->map(function (Transaction $transaction) {
            $date = $transaction->paid_at ?? $transaction->created_at;

            return [
                'type' => 'payment',
                'title' => ($transaction->status === 'paid' ? 'Paiement encaisse' : 'Paiement en attente') . ' - ' . ($transaction->organization->organization_name ?? 'Organisation'),
                'meta' => strtoupper($transaction->source ?? 'n/a') . ' - ' . number_format($transaction->amount, 0, ',', ' ') . ' F',
                'time' => optional($date)->diffForHumans() ?? 'Recent',
                'sort_at' => $date ?? now(),
            ];
        });

        $postFeed = $latestPosts->map(function (Post $post) {
            return [
                'type' => 'post',
                'title' => 'Publication - ' . $post->libelle,
                'meta' => $post->user->organization->organization_name ?? 'Sans blog',
                'time' => optional($post->created_at)->diffForHumans() ?? 'Recent',
                'sort_at' => $post->created_at ?? now(),
            ];
        });

        $subscriptionFeed = $expiringSubscriptions->map(function (OrganizationSubscription $subscription) {
            return [
                'type' => 'subscription',
                'title' => 'Echeance proche - ' . ($subscription->organization->organization_name ?? 'Blog'),
                'meta' => $subscription->days_left > 0 ? $subscription->days_left . ' jours restants' : 'Expire',
                'time' => optional($subscription->expires_at)->diffForHumans() ?? 'A surveiller',
                'sort_at' => $subscription->expires_at ?? now(),
            ];
        });

        return collect([...$paymentFeed, ...$postFeed, ...$subscriptionFeed])
            ->sortByDesc('sort_at')
            ->take(10)
            ->values();
    }
}
