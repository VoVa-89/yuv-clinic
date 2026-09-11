<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $base = rtrim((string) config('app.url'), '/');

        $urls = [
            ['loc' => $base.'/', 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => $base.route('about', [], false), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $base.route('services.index', [], false), 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => $base.route('prices.index', [], false), 'changefreq' => 'weekly', 'priority' => '0.85'],
            ['loc' => $base.route('doctors.index', [], false), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $base.route('reviews.index', [], false), 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['loc' => $base.route('documents', [], false), 'changefreq' => 'yearly', 'priority' => '0.5'],
            ['loc' => $base.route('contacts', [], false), 'changefreq' => 'yearly', 'priority' => '0.7'],
            ['loc' => $base.route('privacy', [], false), 'changefreq' => 'yearly', 'priority' => '0.3'],
        ];

        foreach (Service::query()->published()->get() as $s) {
            $urls[] = [
                'loc' => $base.route('services.show', $s, false),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        }
        foreach (Doctor::query()->published()->get() as $d) {
            $urls[] = [
                'loc' => $base.route('doctors.show', $d, false),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as $u) {
            $xml .= '  <url><loc>'.e($u['loc']).'</loc><changefreq>'.$u['changefreq'].'</changefreq><priority>'.$u['priority'].'</priority></url>'."\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
