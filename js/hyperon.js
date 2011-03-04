	<script type="text/javascript">
		/* <![CDATA[ */

		$(function () {

			// Ajax links
			$("a.ajax").live("click", function (event) {
				event.preventDefault();
				$.get(this.href);
			});

			// Ajax forms
			$("form.ajax").live("submit", function (event) {
				event.preventDefault();
				$(this).ajaxSubmit();
				return false;
			});

			// Ajax submit buttons
			$("form.ajax :submit").live("click", function (event) {
				event.preventDefault();
				$(this).ajaxSubmit();
				return false;
			});

		});

		/* ]]> */
	</script>
