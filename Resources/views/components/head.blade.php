@if (isset($faqCategory->image_head) && Storage::exists($faqCategory->image_head))
    <div id="faq_himg" class="faq_img">
        <img src="{{ asset(Storage::url($faqCategory->image_head)) }}" alt="">
    </div>
@endif
<div id="faq_hhtml">
    <p>
        {!!
            Purifier::clean(
                Utils::isMobile() 
                    ? $faqCategory->mobile_head_html
                    : $faqCategory->head_html
            )
        !!}
    </p>
</div>
