<?php

namespace Modules\Faq\Http\Controllers;

use App\Models\Config;
use App\Service\ImageService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Faq\Entities\Faq;
use Modules\Faq\Entities\FaqCategory;
use Modules\Faq\Http\Requests\Faq\CreateFaqRequest;
use Modules\Faq\Http\Requests\Faq\UpdateFaqRequest;
use Modules\Faq\Http\Requests\FaqCategory\CreateFaqCategoryRequest;
use Modules\Faq\Http\Requests\FaqCategory\UpdateFaqCategoryRequest;

class FaqAdminController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * FAQ 항목 목록 페이지
     *
     * @param FaqCategory $faqCategory
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $faqCategories = FaqCategory::all();
        $faqs = Faq::with('category')
            ->when($request->get('stx'), function ($query, $stx) {
                $query->where('subject', 'LIKE', "%{$stx}%")
                    ->orWhere('content', 'LIKE', "%{$stx}%");
            })
            ->when($request->get('faqCategory'), function($query, $category) {
                $query->where('faq_category_id', $category);
            })
            ->orderBy('position')
            ->get();

        $data = [
            'faqCategories' => $faqCategories,
            'faqs' => $faqs,
            'total' => number_format($faqs->count()),
        ];

        return view('faq::admin.index', $data);
    }

    /**
     * FAQ 항목 등록 페이지
     *
     * @param Faq $faq
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Faq $faq)
    {
        $faqCategories = FaqCategory::withMax('faqs', 'position')->get();

        $data = [
            'title' => 'FAQ 등록',
            'faqCategories' => $faqCategories,
            'faq' => $faq,
            'action' => route('admin.faq.store'),
        ];

        return view('faq::admin.edit', $data);
    }

    /**
     * FAQ 항목 저장
     *
     * @param CreateFaqRequest $request
     * @param Faq $faq
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateFaqRequest $request, Faq $faq)
    {
        $faq->fill($request->safe()->all());
        $faq->save();

        return redirect()->route('admin.faq.index')
            ->with('status', '저장되었습니다.');
    }

    /**
     * FAQ 항목 수정 페이지
     *
     * @param FaqCategory $faqCategory
     * @param Faq $faq
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Faq $faq)
    {
        $faqCategories = FaqCategory::withMax('faqs', 'position')->get();
        
        $data = [
            'title' => 'FAQ 수정',
            'faqCategories' => $faqCategories,
            'faq' => $faq,
            'action' => route('admin.faq.update', ['faq' => $faq]),
        ];

        return view('faq::admin.edit', $data);
    }

    /**
     * FAQ 항목 수정
     *
     * @param UpdateFaqRequest $request
     * @param FaqCategory $faqCategory
     * @param Faq $faq
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateFaqRequest $request, FaqCategory $faqCategory, Faq $faq)
    {
        $faq->fill($request->all());
        $faq->save();

        return redirect()->route('admin.faq.edit', ['faqCategory' => $faqCategory, 'faq' => $faq])
            ->with('status', '수정되었습니다.');
    }
    
    /**
     * FAQ 항목 삭제
     *
     * @param FaqCategory $faqCategory
     * @param Faq $faq
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(FaqCategory $faqCategory, Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faq.index', ['faqCategory' => $faqCategory])
            ->with('status', '삭제되었습니다.');
    }

    /**
     * FAQ 카테고리 목록 페이지
     * 
     * @return Renderable
     */
    public function indexCategory()
    {
        $config = Config::getConfig();
        $paginate = $config->cf_page_rows ?: 10;

        $categories = FaqCategory::withCount('faqs')
            ->orderBy('position')
            ->orderBy('id')
            ->paginate($paginate);

        $data = [
            'total' => number_format($categories->total()),
            'categories' => $categories,
        ];

        return view('faq::admin.category.index', $data);
    }

    /**
     * FAQ 카테고리 등록 페이지
     *
     * @param FaqCategory $faqCategory
     * @return \Illuminate\Contracts\View\View
     */
    public function createCategory(FaqCategory $faqCategory)
    {
        $faqCategory->position = FaqCategory::max('position') + 1;

        $data = [
            'title' => 'FAQ 카테고리 등록',
            'faqCategory' => $faqCategory,
            'action' => route('admin.faq-category.store'),
        ];

        return view('faq::admin.category.edit', $data);
    }

    /**
     * FAQ 카테고리 저장
     *
     * @param CreateFaqCategoryRequest $request
     * @param FaqCategory $faqCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCategory(CreateFaqCategoryRequest $request, FaqCategory $faqCategory)
    {
        $validated = $request->safe()->all();
        $faqCategory->fill($validated);
        // 파일 업로드
        if (isset($validated['image_head'])) {
            $faqCategory->image_head = $this->imageService->uploadImage(public_path('faq_image'), $validated['image_head']);
        }
        if (isset($validated['image_tail'])) {
            $faqCategory->image_tail = $this->imageService->uploadImage(public_path('faq_image'), $validated['image_tail']);
        }
        $faqCategory->save();

        return redirect()->route('admin.faq-category.index')
            ->with('status', '저장되었습니다.');
    }

    /**
     * FAQ 카테고리 수정 페이지
     *
     * @param FaqCategory $faqCategory
     * @return \Illuminate\Contracts\View\View
     */
    public function editCategory(FaqCategory $faqCategory)
    {
        $data = [
            'title' => 'FAQ 카테고리 수정',
            'faqCategory' => $faqCategory,
            'action' => route('admin.faq-category.update', ['faqCategory' => $faqCategory]),
        ];

        return view('admin.faq-category.edit', $data);
    }

    /**
     * FAQ 카테고리 수정
     * 
     * @param UpdateFaqCategoryRequest $request
     * @param FaqCategory $faqCategory
     * @return void
     */
    public function updateCategory(UpdateFaqCategoryRequest $request, FaqCategory $faqCategory)
    {
        $validated = $request->safe()->all();
        $faqCategory->fill($validated);

        // 파일 삭제
        if (isset($validated['image_head_delete'])) {
            if (Storage::delete($faqCategory->image_head)) {
                $faqCategory->image_head = null;
            }
        }
        if (isset($validated['image_tail_delete'])) {
            if (Storage::delete($faqCategory->image_tail)) {
                $faqCategory->image_tail = null;
            }
        }
        // 파일 업로드
        if (isset($validated['image_head'])) {
            $faqCategory->image_head = $this->imageService->uploadImage(public_path('faq_image'), $validated['image_head']);
        }
        if (isset($validated['image_tail'])) {
            $faqCategory->image_tail = $this->imageService->uploadImage(public_path('faq_image'), $validated['image_tail']);
        }
        $faqCategory->save();

        return redirect()->route('admin.faq-category.edit', ['faqCategory' => $faqCategory])
            ->with('status', '수정되었습니다.');
    }
    
    /**
     * FAQ 카테고리 삭제
     *
     * @param FaqCategory $faqCategory
     * @return void
     */
    public function destroyCategory(FaqCategory $faqCategory)
    {
        DB::transaction(function () use ($faqCategory){
            $faqCategory->delete();
            $faqCategory->faqs()->delete();
        });

        return redirect()->route('admin.faq-category.index')
            ->with('status', '삭제되었습니다.');
    }
}
