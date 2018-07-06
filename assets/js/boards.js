/*
 * NPChan
 * Author: Aadarsha Paudel
 * License: GNU GPL v3
 * URL: http://github.com/npchan/npchan
 * -----------------------------------------
 * NPChan Boards Helper JS
 * -----------------------------------------
 * Contributions:
 *
 * -----------------------------------------
 * This code helps to perform various options on boards and threads page.
 * I know some of the code here aren't efficient but they just werks. (most of the time)
 *
*/



	/* Prevent Image Loading if Enlarging/Minimizing */
	$("a.full_image").click(function(e){
		e.preventDefault();
	});

	$("body").append("<div id=\"all_videos\"></div>");

	/* Why am I putting this as a function when it already works with .toggle, I don't know */
	function toggle_item(item){
		$(item).toggle();
	}

	/* Toggle Image Size */
	function toggle_image(item){
		elem=$("#img_"+item);

		// is this video or image?
		if(elem.data('type')=="image"){

			if(elem.data('state')=="thumbnail"){
				elem.prop("src", "/" + elem.data("full"));
				elem.data("state", "full");
			}
			else{
				elem.prop("src", "/" + elem.data("cache"));
				elem.data("state", "thumbnail");
			}

		}

		else{

				var vid=document.getElementById("vid_"+item);

				if(elem.data('state')=="thumbnail"){
					elem.hide();
					elem.data("state", "full");
					$("#source_"+item).prop("src", "/" + elem.data("full"));
					$("#vid_holder_"+item).show();
					vid.play();
				}
				else{
					elem.show();
					$("#vid_holder_"+item).hide();
					elem.data("state", "thumbnail");
					vid.pause();

				}
		}
	}

	/* Toggle Dropdown */
	function dropdown_toggle(item) {
		$("#dd_" + item).toggleClass("show");
		$("#ar" + item).toggleClass("rotate");
	}

	// Close the dropdown menu if the user clicks outside of it
	window.onclick = function(event) {
	  if (!event.target.matches('.dr a') && !event.target.matches('.dropdown_text')) {
	    var dropdowns = document.getElementsByClassName("dropdown-content");
	    var i;
	    for (i = 0; i < dropdowns.length; i++) {
	      var openDropdown = dropdowns[i];
	      if (!openDropdown.classList.contains('hide')) {
	        openDropdown.classList.add('hide');
	        $("span").removeClass("rotate");
	      }
	    }
	  }


		if(event.target.matches('video')){
			var vid_id=$(event.target).attr("id");
			var vid=document.getElementById(vid_id);
			if(vid.paused==true){
				vid.play();
			}
			else{
				vid.pause();
			}

		}

	}

	/* Get Selected Text (For Quoting) */
	function getSelectionText() {
	    var text = "";
	    if (window.getSelection) {
	        text = window.getSelection().toString();
	    } else if (document.selection && document.selection.type != "Control") {
	        text = document.selection.createRange().text;
	    }
	    return text;
	}

	/* Quick Reply */
	function quick_reply(value){
		if(value=="NO"){
			value = "";
		}
		var id="";
		var id2="\n";

		if($("#qr_text").val()!=""){
			var id = "\n";
		}
		let sel="";

		if(getSelectionText()!=''){
			sel=">" + getSelectionText() + "\n";
		}
		$("#qr_text").val($("#qr_text").val() + id + ">>" + value + id2 + sel);
		$("#quickreply").show();
		$("#qr_id").html(value);
		$("#quickreply").draggable({ handle: ".quick_header" });
		$("#qr_thread").val(value);
		$('#qr_text').focus();
	}

	/* Closing Quick Reply */
	function close_qr(){
		$('#qr_text').val("");
		$('#qr_file').val("");
		$('#qerror').slideUp().html("");
		$("#quickreply").slideUp();
	}

	/* Highlight a Thread */
	function highlight(id){
		$(".highlight").removeClass("highlight");
		$("#p"+id).addClass("highlight");
		$("#pr"+id).addClass("highlight");
	}

	/* Cite Function */
	$(".cite").mouseover(function(e){
		$(".cite").css("cursor", "pointer");
		let id=$(this).data("id");

		if($(".hidden").hasClass("h"+id)){
			let data=$("#h"+id).html();
		}
		else if($("div").hasClass("p"+id)){
			let data=$("#p"+id).html();
			$(".preview").append("<div class=\"hidden h"+id+"\" id=\"h"+id+"\">"+data+"</div>");
		}
		else{
			// send ajax and get the data
			let formData = {
			'for' : "thread-preview",
			'thread_id': id
			};
			/* Process The Form */
			$.ajax({
			    type: 'POST',
			    url: '/ajax.php',
			    data : formData,
			}).done(function(response) {
				$(".cite").css("cursor", "pointer");
				let data=response;
				let error=false;
				$(".preview").append("<div class=\"hidden h"+id+"\" id=\"h"+id+"\">"+data+"</div>");
			}).fail(function(data){
		    	let error=true;
		    	console.log("Error: "+ data);
			});
		}

		$(".hidden").hide();
		px=e.pageX+5;
		py=e.pageY-5;
		// show the data
		let data=$("#h"+id).html();
		$(".float").show().html("<div class=\"floating reply\" style=\"top:"+py+"px;left:"+px+"px\">"+data+"</div>");

	});
	$(".cite").mouseout(function(e){
		$(".float").hide();
	});

	function notify(text){
		$("#slide_notify").slideDown().html("Your reply has been posted!");
		setTimeout(function(){
			$("#slide_notify").slideUp();
		}, 5000);
	}

	// adding ajax tag

	$("#quickreply form").attr("src", "/post.php?ajax=true"); // showing the request is indeed ajax

	$("#quickreply form").on("submit", function(e){
		e.preventDefault(); //prevent default submission by browser
		$("#qr_submit").attr("disabled", true);
		var form_data = new FormData($('#quickreply form')[0]);
		$.ajax({
		    type:'POST',				// submission type
		    url:'/post.php?ajax=true',	// url to send data
		    processData: false,			//processing data before submitting (ususally breaks image upload)
		    contentType: false,			// not defining content type
		    async: true,				// run on background
		    cache: false,				// save request on browser?
		    data : form_data			// data to send
		  }).done(function(response){
		  	if(response=="done"){
				close_qr();
				notify("Your reply has been posted!");
				window.location.reload();
				$("#qr_submit").attr("disabled", false);
			} else{
				$("#qerror").show().html(response);
				$("#qr_submit").attr("disabled", false);
			}

				// reloading the page
		}).fail(function(data){
			console.log("Error submitting Form: " + data);
			$("#qerror").show().html("Error! Please try again!");
			$("#qr_submit").attr("disabled", false);
		});
	});
