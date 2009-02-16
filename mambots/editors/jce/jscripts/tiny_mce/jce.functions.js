var jceFunctions = {
	relative : true,
	mambotMode : false,
	state : 'mceEditor',
	toggle : 'код',
	getURL : function(){
		var url = document.location.href;
		return url.substring(0, url.lastIndexOf('/'));
	},
	save : function(html){
		var base_url = tinyMCE.settings['document_base_url'];
		if(this.relative){
			//Links
			html = tinyMCE.regexpReplace(html, 'href\s*=\s*"?' + base_url + '', 'href="', 'gi');
			//Images/Embed
			html = tinyMCE.regexpReplace(html, 'src\s*=\s*"?' + base_url + '', 'src="', 'gi');
			//Object
			html = tinyMCE.regexpReplace(html, 'value\s*=\s*"?' + base_url + '', 'value="', 'gi');
			html = tinyMCE.regexpReplace(html, 'url\s*=\s*"?' + base_url + '', 'url="', 'gi');
			//Media Manager Script Mode rewrites
			html = tinyMCE.regexpReplace(html, 'src:\'' + base_url + '', 'src:\'', 'gi');
			html = tinyMCE.regexpReplace(html, 'url:\'' + base_url + '', 'url:\'', 'gi');
		}
		if(this.mambotMode){
			html = tinyMCE.regexpReplace(html, "&#39;", "'", "gi");
			html = tinyMCE.regexpReplace(html, "&apos;", "'", "gi");
			html = tinyMCE.regexpReplace(html, "&amp;", "&", "gi");
			html = tinyMCE.regexpReplace(html, "&quot;", '"', "gi");
		}
		html = tinyMCE.regexpReplace(html, '<br type="_moz" />', '', "gi");
		return html;
	},
	setCookie : function(id, state){
		document.cookie = "jce_editor_state_"+  id  +"=" + state + "";
	},
	getCookie : function(id){
		var c = 'jce_editor_state_'+id;
		var re = new RegExp( "(\;|^)[^;]*(" + c + ")\=([^;]*)(;|$)" );
		var r = re.exec( document.cookie );
		return r != null ? r[3] : this.state;	
	},
	initEditorMode : function(id){
		document.getElementById(id).className = this.state;
		var state = this.getCookie(id);
		if(document.getElementById(id).className != state){
			switch(state){
				case 'mceEditor':
					document.getElementById(id).className = state;
					break;
				case 'mceNoEditor':
					document.getElementById(id).className = state;
				break;
			}
		}
	},
	toggleEditor : function(id) {
		if (tinyMCE.getInstanceById(id) == null){
			tinyMCE.execCommand('mceAddControl', false, id);
			this.setCookie(id, 'mceEditor');
		}else{
			tinyMCE.execCommand('mceRemoveControl', false, id);
			this.setCookie(id, 'mceNoEditor');
		}
	}
};

