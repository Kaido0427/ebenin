
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($sitemaps as $sitemap)
        <sitemap>
            <loc>{{ url(Storage::url($sitemap)) }}</loc>
            <lastmod>{{ date('c', Storage::disk('public')->lastModified($sitemap)) }}</lastmod>
        </sitemap>
    @endforeach 
</sitemapindex>
