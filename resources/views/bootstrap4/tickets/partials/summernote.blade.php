@if(editor_enabled())

@if(codemirror_enabled())
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.48.4/mode/xml/xml.min.js"></script>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.min.js"></script>
{{-- @if($editor_locale) --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/lang/summernote-{{$editor_locale}}.min.js"></script> --}}
{{-- @endif --}}
<script>


    $(function() {

        var options = $.extend(true, {lang: 'en' {!! codemirror_enabled() ? ", codemirror: {theme: '{codemirror_theme()}', mode: 'text/html', htmlMode: true, lineWrapping: true}" : ''  !!} });

        $("textarea.summernote-editor").summernote(options);

        $("label[for=content]").click(function () {
            $("#content").summernote("focus");
        });
    });


</script>
@endif