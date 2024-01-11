

function setLoadingBtn(btn,txt='')
{
    btn.html('<i class="fa fa-refresh fa-spin fa-fw"></i> '+txt).attr('disabled',true);
}
function resetLoadingBtn(btn,htm=null)
{
    if (htm==null) {
        btn.html('');        
    }else{
        btn.html(htm).removeAttr('disabled');
    }
}
function errorMsg(txt){
    if(typeof txt =='object'){
        $.each(txt,function(i,v){
            toastr.error(v).css({"width": "400px","max-width": "400px" });
        });
    }else{
        toastr.error(txt).css({"width": "400px","max-width": "400px" });
    }
}
function warningMsg(txt){
    
    if(typeof txt =='object'){
        $.each(txt,function(i,v){
            toastr.warning(v).css({"width": "400px","max-width": "400px" });
        });
    }else{
        toastr.warning(txt).css({"width": "400px","max-width": "400px" });
    }
}
function successMsg(txt){
    toastr.success(txt).css({"width": "400px","max-width": "400px" });
}

function debounce(func, delay, template) {
    let timeout;
    return function(...args) {
      clearTimeout(timeout);
  
      // call loading animation
      if (template) {
        setLoadingBtn(template);
      }
  
      timeout = setTimeout(function() {
        func.apply(this, args);
      }, delay);
    };
  }