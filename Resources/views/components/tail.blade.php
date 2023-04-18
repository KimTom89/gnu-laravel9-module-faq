<div id="faq_thtml">
    <p>
        {!!
            Purifier::clean(
                Utils::isMobile() 
                    ? $faqCategory->mobile_tail_html
                    : $faqCategory->tail_html
            )
        !!}
    </p>
</div>
@if (isset($faqCategory->image_tail) && Storage::exists($faqCategory->image_tail))
    <div id="faq_timg" class="faq_timg">
        <img src="{{ asset(Storage::url($faqCategory->image_tail)) }}" alt="">
    </div>
@endif
