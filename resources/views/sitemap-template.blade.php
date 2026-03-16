<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('https://' . $organization->subdomain . '.e-benin.com/blog') }}</loc>
        <lastmod>{{ $organization->updated_at->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    @foreach ($posts as $post)
        <url>
            <loc>{{ url('https://' . $organization->subdomain . '.e-benin.com/post/' . $post->id) }}</loc>
            <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url> 
    @endforeach
</urlset>
