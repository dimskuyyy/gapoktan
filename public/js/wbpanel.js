$.ajaxSetup({
    headers:{'X-CSRF-TOKEN':$("meta[name='X-CSRF-TOKEN']").attr('content')}
});