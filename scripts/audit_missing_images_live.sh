#!/usr/bin/env bash
set -euo pipefail

usage() {
  cat <<'EOF'
Usage:
  scripts/audit_missing_images_live.sh [--domain com|bj|all]

Options:
  --domain   Scope to scan. Default: all

Output:
  /tmp/ebenin_missing_images_live_<timestamp>.csv
EOF
}

DOMAIN="all"
while [[ $# -gt 0 ]]; do
  case "$1" in
    --domain)
      [[ $# -lt 2 ]] && { echo "Missing value for --domain" >&2; exit 1; }
      DOMAIN="$2"
      shift 2
      ;;
    --domain=*)
      DOMAIN="${1#*=}"
      shift
      ;;
    -h|--help)
      usage
      exit 0
      ;;
    *)
      echo "Unknown argument: $1" >&2
      usage
      exit 1
      ;;
  esac
done

case "$DOMAIN" in
  com|bj|all) ;;
  *)
    echo "Invalid --domain value: $DOMAIN (expected: com|bj|all)" >&2
    exit 1
    ;;
esac

for cmd in curl perl mktemp; do
  command -v "$cmd" >/dev/null 2>&1 || {
    echo "Missing required command: $cmd" >&2
    exit 1
  }
done

TS="$(date +%Y%m%d_%H%M%S)"
CSV_OUT="/tmp/ebenin_missing_images_live_${TS}.csv"
MAX_PAGES=1500

perl - "$DOMAIN" "$CSV_OUT" "$MAX_PAGES" <<'PERL'
use strict;
use warnings;
use URI;

my ($domain, $csv_out, $max_pages) = @ARGV;

sub csv_escape {
    my ($v) = @_;
    $v = "" unless defined $v;
    $v =~ s/\r/ /g;
    $v =~ s/\n/ /g;
    $v =~ s/"/""/g;
    return qq{"$v"};
}

sub sh_quote {
    my ($s) = @_;
    $s = "" unless defined $s;
    $s =~ s/'/'"'"'/g;
    return "'$s'";
}

sub host_of {
    my ($url) = @_;
    return URI->new($url)->host // "";
}

sub path_of {
    my ($url) = @_;
    my $u = URI->new($url);
    my $p = $u->path;
    $p = "/" if !defined($p) || $p eq "";
    return $p;
}

sub is_allowed_host {
    my ($host, $scope) = @_;
    return 0 if !$host;
    if ($scope eq "all") {
        return ($host eq "e-benin.com" || $host eq "e-benin.bj" ||
                $host =~ /\.e-benin\.com$/ || $host =~ /\.e-benin\.bj$/) ? 1 : 0;
    }
    if ($scope eq "com") {
        return ($host eq "e-benin.com" || $host =~ /\.e-benin\.com$/) ? 1 : 0;
    }
    return ($host eq "e-benin.bj" || $host =~ /\.e-benin\.bj$/) ? 1 : 0;
}

sub is_subdomain_host {
    my ($host) = @_;
    return ($host =~ /\.e-benin\.com$/ || $host =~ /\.e-benin\.bj$/) ? 1 : 0;
}

sub resolve_url {
    my ($raw, $base) = @_;
    return undef if !defined($raw);
    $raw =~ s/&amp;/&/g;
    $raw =~ s/^\s+|\s+$//g;
    return undef if $raw eq "";
    return undef if $raw =~ /^(#|javascript:|mailto:|tel:|data:|about:)/i;
    my $abs = URI->new_abs($raw, $base)->as_string;
    return $abs;
}

sub run_curl {
    my ($cmd) = @_;
    my $out = `$cmd`;
    my $ok = ($? == 0) ? 1 : 0;
    return ($ok, $out);
}

sub fetch_page {
    my ($url) = @_;
    my $cmd = "curl -sS -L --max-redirs 5 --connect-timeout 10 --max-time 30 " . sh_quote($url);
    my ($ok, $out) = run_curl($cmd);
    return $ok ? $out : undef;
}

sub extract_links {
    my ($html) = @_;
    my @links;
    while ($html =~ /<a\b[^>]*\bhref\s*=\s*(["'])(.*?)\1/igs) {
        push @links, $2;
    }
    return @links;
}

sub extract_image_refs {
    my ($html) = @_;
    my @refs;
    while ($html =~ /<img\b[^>]*\bsrc\s*=\s*(["'])(.*?)\1/igs) {
        push @refs, $2;
    }
    while ($html =~ /<source\b[^>]*\bsrcset\s*=\s*(["'])(.*?)\1/igs) {
        my $srcset = $2;
        my @items = split(/\s*,\s*/, $srcset);
        for my $item (@items) {
            $item =~ s/^\s+|\s+$//g;
            next if $item eq "";
            my ($u) = split(/\s+/, $item, 2);
            push @refs, $u if defined($u) && $u ne "";
        }
    }
    return @refs;
}

sub check_image {
    my ($url, $img_checked, $img_code, $img_final, $img_error) = @_;
    return if exists $img_checked->{$url};
    $img_checked->{$url} = 1;

    my $head_cmd = "curl -sS -I -L --max-redirs 5 --connect-timeout 10 --max-time 20 " .
                   "-o /dev/null -w '%{http_code}\t%{url_effective}\t%{errormsg}' " .
                   sh_quote($url) . " 2>/dev/null";
    my (undef, $head_out) = run_curl($head_cmd);
    chomp $head_out;
    my ($code, $final, $err) = split(/\t/, $head_out, 3);
    $code  = defined($code)  && $code  ne "" ? $code  : "000";
    $final = defined($final) && $final ne "" ? $final : $url;
    $err   = defined($err)   ? $err : "";

    if ($code eq "000" || $code eq "403" || $code eq "405") {
        my $get_cmd = "curl -sS -L --max-redirs 5 --connect-timeout 10 --max-time 25 " .
                      "-o /dev/null -w '%{http_code}\t%{url_effective}\t%{errormsg}' " .
                      sh_quote($url) . " 2>/dev/null";
        my (undef, $get_out) = run_curl($get_cmd);
        chomp $get_out;
        my ($c2, $f2, $e2) = split(/\t/, $get_out, 3);
        $c2 = defined($c2) && $c2 ne "" ? $c2 : "000";
        if ($c2 ne "000") {
            $code  = $c2;
            $final = defined($f2) && $f2 ne "" ? $f2 : $url;
            $err   = defined($e2) ? $e2 : "";
        }
    }

    $img_code->{$url}  = $code;
    $img_final->{$url} = $final;
    $img_error->{$url} = $err;
}

open my $csv_fh, ">", $csv_out or die "Cannot write CSV $csv_out: $!";
print $csv_fh "domain,page_url,image_url,http_code,error,final_url\n";

my @queue;
my %queued;
my %scanned;

sub add_page {
    my ($url, $scope, $queue_ref, $queued_ref) = @_;
    return if !defined($url) || $url eq "";
    return if $url !~ m{^https?://}i;
    $url =~ s/#.*$//;
    my $host = host_of($url);
    return if !is_allowed_host($host, $scope);
    return if exists $queued_ref->{$url};
    $queued_ref->{$url} = 1;
    push @$queue_ref, $url;
}

add_page("https://e-benin.com", $domain, \@queue, \%queued) if $domain eq "all" || $domain eq "com";
add_page("https://e-benin.bj",  $domain, \@queue, \%queued) if $domain eq "all" || $domain eq "bj";

my %img_checked;
my %img_code;
my %img_final;
my %img_error;

my %page_broken;
my %image_broken;
my %domain_broken;

my $scanned_pages = 0;
my $broken_refs = 0;

while (@queue) {
    my $page_url = shift @queue;
    next if $scanned{$page_url};
    last if $scanned_pages >= $max_pages;
    $scanned{$page_url} = 1;
    $scanned_pages++;

    my $html = fetch_page($page_url);
    if (!defined $html) {
        warn "WARN: failed to fetch page: $page_url\n";
        next;
    }

    my $page_host = host_of($page_url);
    my $page_path = path_of($page_url);
    $page_path =~ s/\?.*$//;

    my @links = extract_links($html);
    for my $raw_href (@links) {
        my $abs_href = resolve_url($raw_href, $page_url);
        next if !defined $abs_href;
        my $href_host = host_of($abs_href);
        next if !is_allowed_host($href_host, $domain);
        my $href_path = path_of($abs_href);
        $href_path =~ s/\?.*$//;

        if (is_subdomain_host($href_host) && $href_path =~ m{^/blog/?$}i) {
            add_page($abs_href, $domain, \@queue, \%queued);
            next;
        }

        if (
            is_subdomain_host($page_host) &&
            $page_path =~ m{^/(blog|category/\d+|post/\d+)/?$}i &&
            is_subdomain_host($href_host) &&
            $href_path =~ m{^/(category/\d+|post/\d+)/?$}i
        ) {
            add_page($abs_href, $domain, \@queue, \%queued);
        }
    }

    my @image_refs = extract_image_refs($html);
    my %page_images;
    for my $raw_img (@image_refs) {
        my $abs_img = resolve_url($raw_img, $page_url);
        next if !defined $abs_img;
        next if $abs_img !~ m{^https?://}i;
        $page_images{$abs_img} = 1;
    }

    for my $img_url (keys %page_images) {
        check_image($img_url, \%img_checked, \%img_code, \%img_final, \%img_error);
        my $code = $img_code{$img_url};
        my $err  = $img_error{$img_url};
        my $final = $img_final{$img_url};

        if ($code eq "000" || $code =~ /^4\d\d$/ || $code =~ /^5\d\d$/) {
            $broken_refs++;
            $page_broken{$page_url}++;
            $image_broken{$img_url}++;
            $domain_broken{$page_host}++;

            print $csv_fh join(
                ",",
                csv_escape($page_host),
                csv_escape($page_url),
                csv_escape($img_url),
                csv_escape($code),
                csv_escape($err),
                csv_escape($final),
            ) . "\n";
        }
    }
}

close $csv_fh;

my $unique_images = scalar keys %img_checked;
my $unique_broken = scalar keys %image_broken;

print "CSV report: $csv_out\n";
print "Pages scanned: $scanned_pages\n";
print "Unique images tested: $unique_images\n";
print "Broken image refs: $broken_refs\n";
print "Unique broken images: $unique_broken\n";

print "\nBroken refs by domain:\n";
if (!%domain_broken) {
    print "- none\n";
} else {
    for my $host (sort { $domain_broken{$b} <=> $domain_broken{$a} } keys %domain_broken) {
        print "- $host: $domain_broken{$host}\n";
    }
}

print "\nTop pages with broken images:\n";
if (!%page_broken) {
    print "- none\n";
} else {
    my $n = 0;
    for my $page (sort { $page_broken{$b} <=> $page_broken{$a} } keys %page_broken) {
        print "- $page ($page_broken{$page})\n";
        last if ++$n >= 10;
    }
}

print "\nTop broken image URLs (most referenced):\n";
if (!%image_broken) {
    print "- none\n";
} else {
    my $n = 0;
    for my $img (sort { $image_broken{$b} <=> $image_broken{$a} } keys %image_broken) {
        print "- $img ($image_broken{$img})\n";
        last if ++$n >= 10;
    }
}
PERL

