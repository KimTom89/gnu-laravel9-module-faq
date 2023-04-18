<x-layout-admin :title="$title">
    <form name="faq_form" action="{{ $action }}" onsubmit="return faq_form_check(this);" method="POST">
        @csrf
        @isset($faq->id)
            @method('PUT')
        @endisset
        <div class="tbl_frm01 tbl_wrap">
            <table>
                <caption>FAQ 자주하시는 질문 항목 입력 관리</caption>
                <colgroup>
                    <col class="grid_4">
                    <col>
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="row"><label for="position">카테고리 선택</label></th>
                        <td>
                            <select id="faq_category_id" name="faq_category_id" class="frm_input">
                                <option value="">선택</option>
                                @foreach ($faqCategories as $category)
                                    <option value="{{ $category->id }}" @selected(Utils::isSelectedOld($category->id, 'faq_category_id', $category->id))>
                                        {{ $category->subject }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="position">출력순서</label></th>
                        <td>
                            <span class="frm_info">숫자가 작을수록 FAQ 페이지에서 먼저 출력됩니다.</span>
                            <input type="text" id="position" name="position" class="frm_input"
                                value="{{ old('position', $faq->position) }}" maxlength="10" size="10">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">질문</th>
                        <td>
                            <x-editor content="{{ old('subject', $faq->subject) }}" tagName="subject" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">답변</th>
                        <td>
                            <x-editor content="{{ old('content', $faq->content) }}" tagName="content" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="btn_fixed_top">
            <input type="submit" class="btn_submit btn" value="확인" accesskey="s">
            <a href="{{ route('admin.faq.index') }}" class="btn btn_02">목록</a>
        </div>

    </form>

    <script>
        function faq_form_check(f) {
            errmsg = "";
            errfld = "";

            f.subject.value = getEditorContent('subject');
            f.content.value = getEditorContent('content');

            check_field(f.subject, "제목을 입력하세요.");
            check_field(f.content, "내용을 입력하세요.");

            if (errmsg != "") {
                alert(errmsg);
                errfld.focus();
                return false;
            }

            return true;
        }

        // document.getElementById('fa_order').focus(); 포커스 해제
    </script>
</x-layout-admin>
