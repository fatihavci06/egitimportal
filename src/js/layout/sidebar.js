"use strict";

// Class definition
var KTAppSidebar = function () {
	// Private variables
	var navWrapper;
	var projectsCollapse;
	var header;
	var toggle;

	// Private functions
	// Handle sidebar minimize mode toggle
	var handleToggle = function () {
		var toggleObj = KTToggle.getInstance(toggle);

		if ( toggleObj === null) {
			return;
		}

		// Store sidebar minimize state in cookie
		toggleObj.on('kt.toggle.changed', function() {
			// In server side check sidebar_minimize_state cookie 
			// value and add data-kt-app-sidebar-minimize="on" 
			// attribute to Body tag and "active" class to the toggle button
			var date = new Date(Date.now() + 30 * 24 * 60 * 60 * 1000); // 30 days from now

			KTCookie.set("sidebar_minimize_state", toggleObj.isEnabled() ? "on" : "off", {expires: date}); 
		});
	}

	var handleMenuScroll = function() {
		var menuActiveItem = navWrapper.querySelector(".menu-link.active");

		if ( !menuActiveItem ) {
			return;
		} 

		if ( KTUtil.isVisibleInContainer(menuActiveItem, navWrapper) === true) {
			return;
		}

		navWrapper.scroll({
			top: KTUtil.getRelativeTopPosition(menuActiveItem, navWrapper),
			behavior: 'smooth'
		});
	}

	var handleProjectsCollapse = function() {
		projectsCollapse.addEventListener('shown.bs.collapse', event => {
			const scrollHeight = parseInt(KTUtil.css(navWrapper, "height"));
			navWrapper.scroll({
				top: scrollHeight,
				behavior: 'smooth'
			});
		})
	}

	var handleProjectsSelection = function() {
		const selected = header.querySelector('[data-kt-element="selected"]');
		const menu = header.querySelector('[data-kt-menu="true"]');

		// Toggle Handler
		KTUtil.on(header, '[data-kt-element="project"]', 'click', function (e) {
			const logo = this.querySelector('[data-kt-element="logo"]');
			const title = this.querySelector('[data-kt-element="title"]');
			const desc = this.querySelector('[data-kt-element="desc"]');		
			const selectedLink = menu.querySelector('.menu-link.active');

			selected.querySelector('[data-kt-element="logo"]').setAttribute("src", logo.getAttribute("src"));
			selected.querySelector('[data-kt-element="title"]').innerText = title.innerText;
			selected.querySelector('[data-kt-element="desc"]').innerText = desc.innerText;

			if (selectedLink) {
				selectedLink.classList.remove('active');
			}

			this.classList.add('active');
		});
	}

	// Public methods
	return {
		init: function () {
			// Elements
			navWrapper = document.querySelector('#kt_app_sidebar_main_wrappers');
			projectsCollapse = document.querySelector('#kt_app_sidebar_menu_projects_collapse');
			header = document.querySelector('#kt_app_sidebar_header');
			toggle = document.querySelector('#kt_app_sidebar_toggle');

			if ( toggle ) {
				handleToggle();	
			}
			
			if ( navWrapper ) {
				handleMenuScroll();
			}

			if ( projectsCollapse ) {
				handleProjectsCollapse();
			}

			if ( header ) {
				handleProjectsSelection();
			}
		}
	};
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
	KTAppSidebar.init();
});