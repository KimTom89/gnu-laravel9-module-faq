<x-layout :title="'FAQ ' . $faqCategory->subject">
    <x-slot:css>
        <link rel="stylesheet" href="{{ asset('css/theme/faq/default.css') }}">
    </x-slot:css>
    <x-slot:script>
        <script src="{{ asset('/js/viewimageresize.js') }}"></script>
    </x-slot:script>

    <h2 id="container_title"><span title="{{ $faqCategory->subject }}">{{ $faqCategory->subject }}</span></h2>

    <x-faq::head :faqCategory="$faqCategory"/>

    <fieldset id="faq_sch">
        <legend>FAQ 검색</legend>
        <form name="faq_search_form" method="GET">
            <span class="sch_tit">FAQ 검색</span>
            <input type="hidden" name="faq_category_id" value="{{ $faqCategory->id }}">
            <label class="sound_only" for="stx">검색어<strong class="sound_only"> 필수</strong></label>
            <input type="text" id="stx" name="stx" class="frm_input" value="{{ Request::get('stx') }}" size="15" maxlength="15">
            <button type="submit" class="btn_submit" value="검색">
                <i class="fa fa-search" aria-hidden="true"></i>
                검색
            </button>
        </form>
    </fieldset>

    <nav class="bo_cate">
        <h2>자주하시는질문 분류</h2>
        <ul id="bo_cate_ul">
            @foreach ($categories as $category)
                <li>
                    <a href="{{ route('faq.index', ['faqCategory' => $category] )}}" @class(['bo_cate_on' => $category->id == $faqCategory->id])>
                        <span class="sound_only">열린 분류 </span>
                        {{ $category->subject }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    <div id="faq_wrap" class="faq_1">
        @if ($faqs->count() > 0)
            <section id="faq_con">
                <h2>{{ $faqCategory->subject }} 목록</h2>
                <ol>
                    @foreach ($faqs as $faq)
                        <li>
                            <h3>
                                <span class="tit_bg">Q</span>
                                <a href="#none" onclick="return faq_open(this);">
                                    <p>{!! Purifier::clean($faq->subject) !!}</p>
                                </a>
                                <button class="tit_btn" onclick="return faq_open(this);">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    <span class="sound_only">열기</span>
                                </button>
                            </h3>
                            <div class="con_inner">
                                <p>{!! Purifier::clean($faq->content) !!}</p>
                                <button type="button" class="closer_btn">
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                    <span class="sound_only">닫기</span>
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </section>
        @else
            <div class="empty_list">
                등록된 FAQ가 없습니다.
            </div>
        @endif
    </div>

    <x-faq::tail :faqCategory="$faqCategory"/>

    <script>
        jQuery(function() {
            $(".closer_btn").on("click", function() {
                $(this).closest(".con_inner").slideToggle('slow', function() {
                    var $h3 = $(this).closest("li").find("h3");

                    $("#faq_con li h3").removeClass("faq_li_open");
                    if ($(this).is(":visible")) {
                        $h3.addClass("faq_li_open");
                    }
                });
            });
        });

        function faq_open(el) {
            var $con = $(el).closest("li").find(".con_inner"),
                $h3 = $(el).closest("li").find("h3");

            if ($con.is(":visible")) {
                $con.slideUp();
                $h3.removeClass("faq_li_open");
            } else {
                $("#faq_con .con_inner:visible").css("display", "none");

                $con.slideDown(function() {
                    // 이미지 리사이즈
                    $con.viewimageresize2();
                    $("#faq_con li h3").removeClass("faq_li_open");
                    $h3.addClass("faq_li_open");
                });
            }
            return false;
        }
    </script>
</x-layout>
