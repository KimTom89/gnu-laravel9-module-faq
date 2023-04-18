<x-layout-admin :title="'FAQ 목록'">
    <div class="local_ov01 local_ov">
        <span class="btn_ov01">
            <span class="ov_txt"> 전체</span>
            <span class="ov_num"> {{ $total }}건</span>
        </span>
    </div>

    <form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="GET">
        <select id="faqCategory" name="faqCategory">
            <option value="">카테고리 선택</option>
            @foreach ($faqCategories as $category)
                <option value="{{ $category->id }}" @selected(Request::get('faqCategory') == $category->id)>
                    {{ $category->subject }}
                </option>
            @endforeach
        </select>
        <label class="sound_only" for="stx">검색어<strong class="sound_only"> 필수</strong></label>
        <input type="text" id="stx" name="stx" class="required frm_input" value="{{ Request::get('stx') }}">
        <input type="submit" class="btn_submit" value="검색">
    </form>

    <div class="local_desc01 local_desc">
        <ol>
            <li><strong>FAQ 추가</strong>를 눌러 자주하는 질문과 답변을 입력합니다.</li>
        </ol>
    </div>

    <div class="btn_fixed_top">
        <a href="{{ route('admin.faq.create') }}" class="btn btn_01">FAQ 추가</a>
    </div>

    <div id="itemqalist" class="tbl_head01 tbl_wrap">
        <table>
            <caption>FAQ 목록</caption>
            <thead>
                <tr>
                    <th scope="col">번호</th>
                    <th scope="col">카테고리</th>
                    <th scope="col">제목</th>
                    <th scope="col">순서</th>
                    <th scope="col">관리</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($faqs as $faq)
                    <tr class="bg{{ (int) $loop->even }}">
                        <td class="td_num">{{ $faq->id }}</td>
                        <td class="td_mng_l">{{ $faq->category->subject }}</td>
                        <td class="td_left">
                            <a href="#" target="{{ $faq->id }}" class="qa_href" onclick="return false;">
                                {{ $faq->subject }}
                                <span class="tit_op">열기</span>
                            </a>
                            <div id="faq_div{{ $faq->id }}" class="qa_div" style="display: none;">
                                <div class="qa_q">
                                    <strong>문의내용</strong>
                                    <p>{!! Purifier::clean($faq->subject) !!}</p>
                                </div>
                                <div class="qa_a">
                                    <strong>답변</strong>
                                    <p>{!! Purifier::clean($faq->content) !!}</p>
                                </div>
                            </div>
                        </td>
                        <td class="td_num">{{ $faq->position }}</td>
                        <td class="td_mng td_mng_m">
                            <a href="{{ route('admin.faq.edit', ['faq' => $faq]) }}" class="btn btn_03">
                                <span class="sound_only">{{ $faq->subject }}</span>수정
                            </a>
                            <button class="btn btn_02"  onclick="return delete_faq_confirm(this);"
                                data-action='{{ route('admin.faq.destroy', ['faq' => $faq]) }}'>
                                <span class="sound_only">{{ $faq->subject }}</span>삭제
                            </button>
                        </td>
                    </tr>
                @empty
                    <td class="empty_table" colspan="5">자료가 없습니다.</td>
                @endforelse
            </tbody>
        </table>
    </div>

    <form id="delete_faq_form" name="delete_faq_form" method="POST" action="">
        @csrf
        @method('DELETE')
    </form>

    <script>
        $(function() {
            $("#faqCategory").on('change', function(){
                $("#fsearch").submit();
            });


            $(".qa_href").click(function() {
                var $content = $("#faq_div" + $(this).attr("target"));
                $(".qa_div").each(function(index, value) {
                    if ($(this).get(0) == $content.get(0)) {
                        $(this).is(":hidden") ? $(this).show() : $(this).hide();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });

        function delete_faq_confirm(el) {
            if (confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
                $("#delete_faq_form").attr('action', $(el).data('action'));
                $("#delete_faq_form").submit();
                return true;
            } else {
                return false;
            }
        }
    </script>

</x-layout-admin>
