<x-layout-admin :title="'FAQ 카테고리 관리'">
    <div class="local_ov01 local_ov">
        <span class="btn_ov01">
            <span class="ov_txt"> 전체 </span>
            <span class="ov_num"> {{ $total }}건</span>
        </span>
    </div>
    <div class="local_desc01 local_desc">
        <ol>
            <li><strong>FAQ 카테고리 추가</strong> 버튼을 눌러 FAQ 카테고리를 생성합니다. (자주하시는 질문, 이용안내 등)</li>
            <li>생성한 FAQ 카테고리의 <strong>제목</strong> 또는 <strong>항목</strong> 눌러 세부항목을 관리할 수 있습니다.</li>
        </ol>
    </div>
    <div class="btn_fixed_top">
        <a href="{{ route('admin.faq-category.create') }}" class="btn_01 btn">FAQ 카테고리 추가</a>
    </div>
    <div class="tbl_head01 tbl_wrap">
        <table>
            <caption>FAQ관리 목록</caption>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">제목</th>
                    <th scope="col">FAQ수</th>
                    <th scope="col">순서</th>
                    <th scope="col">관리</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr class="bg{{ (int)$loop->even }}">
                        <td class="td_num">{{ $category->id }}</td>
                        <td class="td_left"><a href="{{ route('admin.faq.index', ['faqCategory' => $category]) }}">{{ $category->subject }}</a></td>
                        <td class="td_num">{{ $category->faqs_count }}</td>
                        <td class="td_num">{{ $category->position }}</td>
                        <td class="td_mng td_mng_l" style="width:200px;">
                            <a href="{{ route('admin.faq-category.edit', ['faqCategory' => $category]) }}" class="btn btn_03">
                                <span class="sound_only">{{ $category->subject }}</span>수정
                            </a>
                            <a href="{{ route('admin.faq.index', ['faqCategory' => $category]) }}" class="btn btn_03">
                                <span class="sound_only">{{ $category->subject }}</span>항목
                            </a>
                            <a href="{{ route('faq.index', ['faqCategory' => $category]) }}" class="btn btn_02" target="_blank">
                                <span class="sound_only">{{ $category->subject }}</span>보기
                            </a>
                            <button class="btn btn_02" onclick="return delete_faq_category_confirm(this);"
                                data-action="{{ route('admin.faq-category.destroy', ['faqCategory' => $category]) }}">
                                <span class="sound_only">{{ $category->subject }}</span>삭제
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty_table"><span>자료가 한건도 없습니다.</span></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $categories->links('paginate.admin.default', ['onEachSide' => $configDefault->cf_write_pages ?: 10]) }}

    <form id="delete_faq_category_form" name="delete_faq_category_form" method="POST" action="">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function delete_faq_category_confirm(el) {
            if (confirm("한번 삭제한 자료는 복구할 방법이 없습니다. \n(상세내용까지 일괄 삭제) \n \n정말 삭제하시겠습니까?")) {
                $("#delete_faq_category_form").attr('action', $(el).data('action'));
                $("#delete_faq_category_form").submit();
                return true;
            } else {
                return false;
            }
        }
    </script>
</x-layout-admin>
