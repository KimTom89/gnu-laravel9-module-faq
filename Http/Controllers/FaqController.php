<?php

namespace Modules\Faq\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Faq\Entities\Faq;
use Modules\Faq\Entities\FaqCategory;

class FaqController extends Controller
{
    /**
     * FAQ 페이지
     *
     * @param Request $request
     * @param FaqCategory|null $faqCategory
     * @return Renderable
     */
    public function index(Request $request, FaqCategory $faqCategory = null)
    {
        $categories = FaqCategory::orderBy('position')->get();
        if (is_null($categories->first())) {
            return back()->withErrors('FAQ가 등록되지 않았습니다.');
        }

        $faqCategory = ($faqCategory == null) ? $categories->first() : $faqCategory;

        $faqs = Faq::when($request->get('stx'), function($query, $stx){
                $query->where('subject', 'LIKE', "%{$stx}%")
                        ->orWhere('content', 'LIKE', "%{$stx}%");
            })
            ->where('faq_category_id', $faqCategory->id)
            ->orderBy('position')
            ->get();

        $data = [
            'faqCategory' => $faqCategory,
            'categories' => $categories,
            'faqs' => $faqs,
        ];

        return view('faq::index', $data);
    }
}
