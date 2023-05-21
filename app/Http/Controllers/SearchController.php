<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class SearchController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'brand' => ['required'],
            'model' => ['required'],
            'year' => ['required'],
        ]);
        $url = "https://www.autovit.ro/autoturisme/" . $request->brand . "/" . str_replace(' ', '-', $request->model) . "/de-la-" . $request->year . "?search%5Bfilter_float_year%3Ato%5D=" . $request->year . "";
        $prices = [];
        $sum = 0;
        $crawler = \Weidner\Goutte\GoutteFacade::request('GET', $url);
        $pages = $crawler->filter('.e1f09v7o0')
            ->each(function ($node) {
                return $node->text();
            });

        if ($pages) {
            foreach ($pages as $page) {
                $crawler = \Weidner\Goutte\GoutteFacade::request('GET', $url . '&page=' . $page);
                $crawlerData = $crawler->filter('.evg565y11')
                    ->each(function ($node) {
                        Log::alert($node->text());
                        return $node->text();
                    });
                if ($crawlerData) {
                    foreach ($crawlerData as $rawPrice) {
                        $price = str_replace('EUR', '', $rawPrice);
                        $prices[] = str_replace(' ', '', $price);
                    }
                }
            }
        } else {
            $crawlerData = $crawler->filter('.evg565y11')
                ->each(function ($node) {
                    return $node->text();
                });
            if ($crawlerData) {
                foreach ($crawlerData as $rawPrice) {
                    $price = str_replace('EUR', '', $rawPrice);
                    $prices[] = str_replace(' ', '', $price);
                }
            }
        }
        if ($prices) {
            $sum = array_sum($prices);
        }
        Log::alert($url);
        return response()->json([
            'brand' => $request->brand,
            'model' => $request->model,
            'year' => $request->year,
            'sum' => $sum,
            'totalCars' => count($prices),
            'url' => $url
        ], 200);
    }

    public function crawlerResults()
    {
    }
}
