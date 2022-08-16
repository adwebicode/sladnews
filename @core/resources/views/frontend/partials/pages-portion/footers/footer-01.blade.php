<footer class="footer-area style-01">
    <div class="footer-top">
        <div class="container custom-container-01">
            <div class="row">
                  {!! render_frontend_sidebar('footer_01',['column' => true]) !!}
            </div>
        </div>
    </div>

    <div class="footer-bottom dark-bg-03">
        <div class="copyright-area">
            <div class="container custom-container-01">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="copyright-area-inner">
                            <div class="single-copyright-item">
                                {!! purify_html_raw(get_footer_copyright_text()) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
