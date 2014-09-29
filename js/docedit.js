//---------------------------------------------------
function HideEditor()
{
	tinymce.get("editable").remove();	
	document.getElementById("editable_edit").style.display="block";
	document.getElementById("editable_save").style.display="none";				
	document.getElementById("editable_discard").style.display="none";		
}

function EditContent() {
	tinymce.init({
		inline:false,
		selector:"#editable"
	});

	document.getElementById("editable").focus();
	document.getElementById("editable_edit").style.display="none";
	document.getElementById("editable_save").style.display="block";				
	document.getElementById("editable_discard").style.display="block";
}

function SaveContent(fname) {
	var ed = tinymce.get("editable");
	
	if (!ed)
		return false;
		
	var url = "../save_html.php";		
	
	$.post(url, { "content" : ed.getContent(), "token" : fname }, function(response){
	
		if ( response != 0) {
			alert(response);
			return false;
		}
	});		
	HideEditor();
	return true;
}	