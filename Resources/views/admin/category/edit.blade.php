<x-layout-admin :title="$title">
    <form name="faq_category_form" action="{{ $action }}" onsubmit="return faq_category_form_submit(this);" method="POST" enctype="multipart/form-data">
        @csrf
        @isset ($faqCategory->id)
            @method('PUT')
        @endisset
        <div class="tbl_frm01 tbl_wrap">
            <table>
                <caption>FAQ 입력 관리</caption>
                <colgroup>
                    <col class="grid_4">
                    <col>
                </colgroup>
                <tbody>
                    <tr>
                        <th scope="row"><label for="position">출력순서</label></th>
                        <td>
                            <span class="frm_info">숫자가 작을수록 FAQ 분류에서 먼저 출력됩니다.</span>
                            <input type="text" id="position" name="position" class="frm_input"
                                value="{{ old('position') ?: $faqCategory->position }}" maxlength="10" size="10">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="subject">제목</label></th>
                        <td>
                            <input type="text" id="subject" name="subject" class="frm_input required"
                                value="{{ old('subject') ?: $faqCategory->subject }}" size="70" required>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="image_head">상단이미지</label></th>
                        <td>
                            <input type="file" id="image_head" name="image_head">
                            @if (isset($faqCategory->image_head) && Storage::exists($faqCategory->image_head))
                                <input type="checkbox" name="image_head_delete" value="1" id="image_head_delete"><label for="image_head_delete">삭제</label>
                                <div class="banner_or_img">
                                    <img src="{{ asset(Storage::url($faqCategory->image_head)) }}" width="{{ $faqCategory->image_head_width }}" alt="">
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="image_tail">하단이미지</label></th>
                        <td>
                            <input type="file" id="image_tail" name="image_tail">
                            @if (isset($faqCategory->image_tail) && Storage::exists($faqCategory->image_tail))
                                <input type="checkbox" name="image_tail_delete" value="1" id="image_tail_delete"><label for="image_tail_delete">삭제</label>
                                <div class="banner_or_img">
                                    <img src="{{ asset(Storage::url($faqCategory->image_tail)) }}" width="{{ $faqCategory->image_tail_width }}" alt="">
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">상단 내용</th>
                        <td>
                            <x-editor content="{{ old('head_html') ?: $faqCategory->head_html }}" tagName="head_html" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">하단 내용</th>
                        <td>
                            <x-editor content="{{ old('tail_html') ?: $faqCategory->tail_html }}" tagName="tail_html" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">모바일상단 내용</th>
                        <td>
                            <x-editor content="{{ old('mobile_head_html') ?: $faqCategory->mobile_head_html }}" tagName="mobile_head_html" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">모바일하단 내용</th>
                        <td>
                            <x-editor content="{{ old('mobile_tail_html') ?: $faqCategory->mobile_tail_html }}" tagName="mobile_tail_html" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="btn_fixed_top">
            <a href="{{ route('admin.faq.category.index') }}" class="btn btn_02">목록</a>
            <input type="submit" class="btn_submit btn" value="확인" accesskey="s">
        </div>

    </form>

    <script>
        function faq_category_form_submit(f) {
            f.head_html.value = getEditorContent('head_html');
            f.tail_html.value = getEditorContent('tail_html');
            f.mobile_head_html.value = getEditorContent('mobile_head_html');
            f.mobile_tail_html.value = getEditorContent('mobile_tail_html');

            return true;
        }

        // document.frmfaqmasterform.subject.focus();
    </script>
</x-layout-admin>
