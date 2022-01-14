
		<footer class="fixed-bottom text-right pb-3 pr-5">
		</footer>

		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    	<script src="<?= SUB_DIR ?>/bootstrap/js/bootstrap.min.js"></script>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
		<script src="https://use.fontawesome.com/4c8739c9b7.js"></script>
		<script src="<?= SUB_DIR ?>/assets/js/core/jquery.min.js"></script>
		<script src="<?= SUB_DIR ?>/assets/js/core/popper.min.js"></script>
		<script src="<?= SUB_DIR ?>/assets/js/core/bootstrap-material-design.min.js"></script>
		<script src="https://unpkg.com/default-passive-events"></script>
		<script src="<?= SUB_DIR ?>/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
		<script async defer src="https://buttons.github.io/buttons.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
		<script src="<?= SUB_DIR ?>/assets/js/plugins/chartist.min.js"></script>
		<script src="<?= SUB_DIR ?>/assets/js/plugins/bootstrap-notify.js"></script>
		<script src="<?= SUB_DIR ?>/assets/js/material-dashboard.js?v=2.1.0"></script>
		<script src="<?= SUB_DIR ?>/assets/demo/demo.js"></script>
		<script>
	$(document).ready(function() {
		$().ready(function() {
		$sidebar = $('.sidebar');

		$sidebar_img_container = $sidebar.find('.sidebar-background');

		$full_page = $('.full-page');

		$sidebar_responsive = $('body > .navbar-collapse');

		window_width = $(window).width();

		$('.fixed-plugin a').click(function(event) {
			if ($(this).hasClass('switch-trigger')) {
			if (event.stopPropagation) {
				event.stopPropagation();
			} else if (window.event) {
				window.event.cancelBubble = true;
			}
			}
		});

		$('.fixed-plugin .active-color span').click(function() {
			$full_page_background = $('.full-page-background');

			$(this).siblings().removeClass('active');
			$(this).addClass('active');

			var new_color = $(this).data('color');

			if ($sidebar.length != 0) {
			$sidebar.attr('data-color', new_color);
			}

			if ($full_page.length != 0) {
			$full_page.attr('filter-color', new_color);
			}

			if ($sidebar_responsive.length != 0) {
			$sidebar_responsive.attr('data-color', new_color);
			}
		});

		$('.fixed-plugin .background-color .badge').click(function() {
			$(this).siblings().removeClass('active');
			$(this).addClass('active');

			var new_color = $(this).data('background-color');

			if ($sidebar.length != 0) {
			$sidebar.attr('data-background-color', new_color);
			}
		});

		$('.fixed-plugin .img-holder').click(function() {
			$full_page_background = $('.full-page-background');

			$(this).parent('li').siblings().removeClass('active');
			$(this).parent('li').addClass('active');


			var new_image = $(this).find("img").attr('src');

			if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
			$sidebar_img_container.fadeOut('fast', function() {
				$sidebar_img_container.css('background-image', 'url("' + new_image + '")');
				$sidebar_img_container.fadeIn('fast');
			});
			}

			if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
			var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

			$full_page_background.fadeOut('fast', function() {
				$full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
				$full_page_background.fadeIn('fast');
			});
			}

			if ($('.switch-sidebar-image input:checked').length == 0) {
			var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
			var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

			$sidebar_img_container.css('background-image', 'url("' + new_image + '")');
			$full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
			}

			if ($sidebar_responsive.length != 0) {
			$sidebar_responsive.css('background-image', 'url("' + new_image + '")');
			}
		});

		$('.switch-sidebar-image input').change(function() {
			$full_page_background = $('.full-page-background');

			$input = $(this);

			if ($input.is(':checked')) {
			if ($sidebar_img_container.length != 0) {
				$sidebar_img_container.fadeIn('fast');
				$sidebar.attr('data-image', '#');
			}

			if ($full_page_background.length != 0) {
				$full_page_background.fadeIn('fast');
				$full_page.attr('data-image', '#');
			}

			background_image = true;
			} else {
			if ($sidebar_img_container.length != 0) {
				$sidebar.removeAttr('data-image');
				$sidebar_img_container.fadeOut('fast');
			}

			if ($full_page_background.length != 0) {
				$full_page.removeAttr('data-image', '#');
				$full_page_background.fadeOut('fast');
			}

			background_image = false;
			}
		});

		$('.switch-sidebar-mini input').change(function() {
			$body = $('body');

			$input = $(this);

			if (md.misc.sidebar_mini_active == true) {
			$('body').removeClass('sidebar-mini');
			md.misc.sidebar_mini_active = false;

			$('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

			} else {

			$('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

			setTimeout(function() {
				$('body').addClass('sidebar-mini');

				md.misc.sidebar_mini_active = true;
			}, 300);
			}

			var simulateWindowResize = setInterval(function() {
			window.dispatchEvent(new Event('resize'));
			}, 180);

			setTimeout(function() {
			clearInterval(simulateWindowResize);
			}, 1000);

		});
		});
	});
	</script>
	<script>
	$(document).ready(function() {
		md.initDashboardPageCharts();

	});
	</script>

</body>
</html>