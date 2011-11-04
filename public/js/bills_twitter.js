/**
 * bills_twitter.js
 * 
 * @author Lucian Hontau
 * @copyright Lucian Hontau
 *
 * JS for doing ajax calls to dim/undim tweets and to show them properly upon page reload
 */

/**
 * jQuery function to send an ajax request to mark as viewed or unviewed a particular tweet
 * as well as change its transparency
 */
$('document').ready(function() {
	$('input.bills-tweet-checkbox').click(function() {
		var id = $(this).attr('id');
		var state = $(this).attr('checked');
		(state == true) ? state = 1 : state = 0;
		
		var params = {
			id: id.substring(9), //remove the id prefix 'tweet-id-'
			state: state
		}

		var parentDiv = $(this).parents('.six_column, section').children('.bills-tweet-container');

		$.ajax({
		  url: '/default/index/toggle-Tweet-Viewed',
		  dataType: 'json',
		  data: params,
		  success: function(response) {
			  //console.log(response); //debug only

			  //if it's checked we dim and vice versa
			  if(response.state == 1) {
				  $(parentDiv).css('opacity', '.5');
			  }
			  else {
				  $(parentDiv).css('opacity', '1');
			  }
		  }
		});
	});
});