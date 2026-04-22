<!doctype html>
@php
	$host = request()->getHost();
	$baseDomain = str_contains($host, 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
@endphp


<html lang="en" class="no-js">
<head>
	<title>SportNews</title>

	<meta charset="utf-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<link rel="stylesheet" href="css/modernmag-assets.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body class="boxed-style">

	<!-- Container -->
	<div id="container">
		<!-- Header
		    ================================================== -->
		<header class="clearfix">

			<div class="top-line">
				<div class="container">
					<div class="row">
						<div class="col-sm-5">
							<a class="navbar-brand" href="index.html">
								<img src="{{ asset('images/logo.png') }}" alt="">
							</a>
						</div>	
						<div class="col-sm-7">
							<form class="form-inline">
								<input class="form-control mr-sm-2" type="search" placeholder="Search for..." aria-label="Search">
								<button class="btn btn-primary my-2 my-sm-0" type="submit"><i class="fa fa-search"></i></button>
							</form>
							<ul class="info-list right-align">
								<li>
									<i class="fa fa-clock-o"></i>Monday 15.01.2018
								</li>
								@guest
								<li>
									<a href="#" data-toggle="modal" data-target="#loginModal"><i class="fa fa-user"></i>Log in</a>
								</li>
								<li>
									<a href="{{ route('userRegister') }}">Register</a>
								</li>
								@else
								@if(!empty(auth()->user()->organization?->subdomain))
								<li>
									<a href="https://{{ auth()->user()->organization->subdomain }}.{{ $baseDomain }}/dashboard">
										<i class="fa fa-tachometer"></i> Dashboard
									</a>
								</li>
								@endif
								<li>
									<a href="{{ route('logOut') }}" onclick="event.preventDefault(); document.getElementById('logout-form-single').submit();">Log out</a>
									<form id="logout-form-single" action="{{ route('logOut') }}" method="POST" style="display:none;">
										@csrf
									</form>
								</li>
								@endguest
							</ul>
						</div>	 
					</div>
				</div>
			</div>

			<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
				<div class="container">
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>

					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav mr-auto">
							<li class="nav-item active">
								<a class="nav-link" href="index.html">Home</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="category1.html">Football<i class="fa fa-caret-down"></i></a>
								<div class="mega-posts-menu">
									<div class="posts-line">
										<ul class="filter-list">
											<li><a href="#">All</a></li>
											<li><a href="#">La liga</a></li>
											<li><a href="#">Bundesliga</a></li>
											<li><a href="#">Premier League</a></li>
											<li><a href="#">Serie A</a></li>
										</ul>
										<div class="row">
											<div class="col-lg-3 col-md-6">
												<div class="news-post standart-post">
													<div class="post-image">
														<a href="single-post">
															<img src="upload/blog/s1.jpg" alt="">
														</a>
														<a href="#" class="category">Football</a>
													</div>
													<h2><a href="single-post.html">New alternatives are more</a></h2>
													<ul class="post-tags">
														<li><i class="lnr lnr-user"></i>by <a href="#">John Doe</a></li>
														<li><a href="#"><i class="lnr lnr-book"></i><span>23 comments</span></a></li>
													</ul>
												</div>
											</div>
											<div class="col-lg-3 col-md-6">
												<div class="news-post standart-post">
													<div class="post-image">
														<a href="single-post">
															<img src="upload/blog/s2.jpg" alt="">
														</a>
														<a href="#" class="category">Football</a>
													</div>
													<h2><a href="single-post.html">New alternatives are more</a></h2>
													<ul class="post-tags">
														<li><i class="lnr lnr-user"></i>by <a href="#">John Doe</a></li>
														<li><a href="#"><i class="lnr lnr-book"></i><span>23 comments</span></a></li>
													</ul>
												</div>
											</div>
											<div class="col-lg-3 col-md-6">
												<div class="news-post standart-post">
													<div class="post-image">
														<a href="single-post">
															<img src="upload/blog/s24.jpg" alt="">
														</a>
														<a href="#" class="category">Football</a>
													</div>
													<h2><a href="single-post.html">New alternatives are more</a></h2>
													<ul class="post-tags">
														<li><i class="lnr lnr-user"></i>by <a href="#">John Doe</a></li>
														<li><a href="#"><i class="lnr lnr-book"></i><span>23 comments</span></a></li>
													</ul>
												</div>
											</div>
											<div class="col-lg-3 col-md-6">
												<div class="news-post standart-post">
													<div class="post-image">
														<a href="single-post">
															<img src="upload/blog/s25.jpg" alt="">
														</a>
														<a href="#" class="category">Football</a>
													</div>
													<h2><a href="single-post.html">New alternatives are more</a></h2>
													<ul class="post-tags">
														<li><i class="lnr lnr-user"></i>by <a href="#">John Doe</a></li>
														<li><a href="#"><i class="lnr lnr-book"></i><span>23 comments</span></a></li>
													</ul>
												</div>
											</div>
										</div> 
									</div>
								</div>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="category1.html">Basketball<i class="fa fa-caret-down"></i></a>
								<div class="mega-posts-menu">
									<div class="posts-line">
										<ul class="filter-list">
											<li><a href="#">All</a></li>
											<li><a href="#">NBA</a></li>
											<li><a href="#">Europa League</a></li>
											<li><a href="#">Street Ball</a></li>
											<li><a href="#">Spain</a></li>
										</ul>
										<div class="row">
											<div class="col-lg-3 col-md-6">
												<div class="news-post standart-post">
													<div class="post-image">
														<a href="single-post">
															<img src="upload/blog/s4.jpg" alt="">
														</a>
														<a href="#" class="category">Basketball</a>
													</div>
													<h2><a href="single-post.html">New alternatives are more</a></h2>
													<ul class="post-tags">
														<li><i class="lnr lnr-user"></i>by <a href="#">John Doe</a></li>
														<li><a href="#"><i class="lnr lnr-book"></i><span>23 comments</span></a></li>
													</ul>
												</div>
											</div>
											<div class="col-lg-3 col-md-6">
												<div class="news-post standart-post">
													<div class="post-image">
														<a href="single-post">
															<img src="upload/blog/s14.jpg" alt="">
														</a>
														<a href="#" class="category">Basketball</a>
													</div>
													<h2><a href="single-post.html">New alternatives are more</a></h2>
													<ul class="post-tags">
														<li><i class="lnr lnr-user"></i>by <a href="#">John Doe</a></li>
														<li><a href="#"><i class="lnr lnr-book"></i><span>23 comments</span></a></li>
													</ul>
												</div>
											</div>
											<div class="col-lg-3 col-md-6">
												<div class="news-post standart-post">
													<div class="post-image">
														<a href="single-post">
															<img src="upload/blog/s16.jpg" alt="">
														</a>
														<a href="#" class="category">Basketball</a>
													</div>
													<h2><a href="single-post.html">New alternatives are more</a></h2>
													<ul class="post-tags">
														<li><i class="lnr lnr-user"></i>by <a href="#">John Doe</a></li>
														<li><a href="#"><i class="lnr lnr-book"></i><span>23 comments</span></a></li>
													</ul>
												</div>
											</div>
											<div class="col-lg-3 col-md-6">
												<div class="news-post standart-post">
													<div class="post-image">
														<a href="single-post">
															<img src="upload/blog/s19.jpg" alt="">
														</a>
														<a href="#" class="category">Basketball</a>
													</div>
													<h2><a href="single-post.html">New alternatives are more</a></h2>
													<ul class="post-tags">
														<li><i class="lnr lnr-user"></i>by <a href="#">John Doe</a></li>
														<li><a href="#"><i class="lnr lnr-book"></i><span>23 comments</span></a></li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="category1.html">Winter Sports<i class="fa fa-caret-down"></i></a>
								<div class="mega-posts-menu">
									<div class="posts-line">
										<ul class="filter-list">
											<li><a href="#">All</a></li>
											<li><a href="#">Ski Alpine</a></li>
											<li><a href="#">Ski Jumping</a></li>
											<li><a href="#">Biathlon</a></li>
										</ul>
										<div class="row">
											<div class="col-lg-3 col-md-6">
												<div class="news-post standart-post">
													<div class="post-image">
														<a href="single-post">
															<img src="upload/blog/s20.jpg" alt="">
														</a>
														<a href="#" class="category">Winter Sports</a>
													</div>
													<h2><a href="single-post.html">New alternatives are more</a></h2>
													<ul class="post-tags">
														<li><i class="lnr lnr-user"></i>by <a href="#">John Doe</a></li>
														<li><a href="#"><i class="lnr lnr-book"></i><span>23 comments</span></a></li>
													</ul>
												</div>
											</div>
											<div class="col-lg-3 col-md-6">
												<div class="news-post standart-post">
													<div class="post-image">
														<a href="single-post">
															<img src="upload/blog/s22.jpg" alt="">
														</a>
														<a href="#" class="category">Winter Sports</a>
													</div>
													<h2><a href="single-post.html">New alternatives are more</a></h2>
													<ul class="post-tags">
														<li><i class="lnr lnr-user"></i>by <a href="#">John Doe</a></li>
														<li><a href="#"><i class="lnr lnr-book"></i><span>23 comments</span></a></li>
													</ul>
												</div>
											</div>
											<div class="col-lg-3 col-md-6">
												<div class="news-post standart-post">
													<div class="post-image">
														<a href="single-post">
															<img src="upload/blog/s23.jpg" alt="">
														</a>
														<a href="#" class="category">Winter Sports</a>
													</div>
													<h2><a href="single-post.html">New alternatives are more</a></h2>
													<ul class="post-tags">
														<li><i class="lnr lnr-user"></i>by <a href="#">John Doe</a></li>
														<li><a href="#"><i class="lnr lnr-book"></i><span>23 comments</span></a></li>
													</ul>
												</div>
											</div>
											<div class="col-lg-3 col-md-6">
												<div class="news-post standart-post">
													<div class="post-image">
														<a href="single-post">
															<img src="upload/blog/s26.jpg" alt="">
														</a>
														<a href="#" class="category">Winter Sports</a>
													</div>
													<h2><a href="single-post.html">New alternatives are more</a></h2>
													<ul class="post-tags">
														<li><i class="lnr lnr-user"></i>by <a href="#">John Doe</a></li>
														<li><a href="#"><i class="lnr lnr-book"></i><span>23 comments</span></a></li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="category1.html">Athletics</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="category1.html">Teniss</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="category1.html">Handball</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="category1.html">Rugby</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="category1.html">Teniss</a>
							</li>
							<li class="nav-item drop-link">
								<a class="nav-link food" href="#">Pages<i class="fa fa-caret-down"></i></a>
								<ul class="dropdown">
									<li><a href="archive.html">Archive Page</a></li>
									<li><a href="tag.html">Tags Page</a></li>
									<li><a href="search.html">Search Page</a></li>
									<li><a href="register.html">Register Page</a></li>
									<li><a href="about.html">About Us</a></li>
									<li><a href="contact.html">Contact Us</a></li>
									<li><a href="404-error.html">404 Error</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</nav>

		</header>
		<!-- End Header -->

		<!-- content-section 
			================================================== -->
		<section id="content-section">
			<div class="container">

				<div class="row">
					<div class="col-lg-8">
						
						<!-- single-post -->
						<div class="single-post">
							<h1>More than one hundred thousand people evacuated in Indonesia</h1>
							<ul class="post-tags">
								<li><i class="lnr lnr-user"></i>by <a href="#">Helena Doe</a></li>
								<li><a href="#"><i class="lnr lnr-book"></i><span>20 comments</span></a></li>
								<li><i class="lnr lnr-eye"></i>872 Views</li>
							</ul>
							<div class="share-post-box">
								<ul class="share-box">
									<li><a class="facebook" href="#"><i class="fa fa-facebook"></i><span>Share on Facebook</span></a></li>
									<li><a class="twitter" href="#"><i class="fa fa-twitter"></i><span>Share on Twitter</span></a></li>
									<li><a class="google" href="#"><i class="fa fa-google-plus"></i></a></li>
									<li><a class="linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>
									<li><a class="rss" href="#"><i class="fa fa-rss"></i></a></li>
								</ul>
							</div>
							<img src="upload/blog/lg1.jpg" alt="">
							<div class="text-boxes">
								<p>
								Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti. Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. Vestibulum volutpat, <a href="#">lacus a ultrices</a> sagittis, mi neque euismod dui, eu pulvinar nunc sapien ornare nisl. Phasellus pede arcu, dapibus eu, fermentum et, dapibus sed, urna.
								</p>
								<p>
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
									consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non	proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
								</p>
								<h2>Duis aute irure dolor in reprehenderit in voluptate</h2>
								<p>
								Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.
								</p>
							</div>
							<blockquote>
								<p>Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus.</p>
							</blockquote>
							<div class="text-boxes">
								<p>
								Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.
								</p>
								<p>
								Suspendisse mauris. Fusce accumsan mollis eros. Pellentesque a diam sit amet mi ullamcorper vehicula. Integer adipiscing risus a sem. Nullam quis massa sit amet nibh viverra malesuada. Nunc sem lacus, accumsan quis, faucibus non, congue vel, arcu. Ut scelerisque hendrerit tellus. Integer sagittis. Vivamus a mauris eget arcu gravida tristique. Nunc iaculis mi in ante. Vivamus imperdiet nibh feugiat est.
								</p>
							</div>
							<img src="upload/blog/lg2.jpg" alt="">
							<div class="text-boxes">
								<h2>Mauris nibh felis, adipiscing varius, adipiscing in</h2>
								<p>
								Morbi interdum mollis sapien. Sed ac risus. Phasellus lacinia, magna a ullamcorper laoreet, lectus arcu pulvinar risus, vitae facilisis libero dolor a purus. Sed vel lacus. Mauris nibh felis, adipiscing varius, adipiscing in, lacinia vel, tellus. Suspendisse ac urna. Etiam pellentesque mauris ut lectus. Nunc tellus ante, mattis eget, gravida vitae, ultricies ac, leo. Integer leo pede, ornare a, lacinia eu, vulputate vel, nisl.
								</p>
								<p>
								Suspendisse mauris. Fusce accumsan mollis eros. Pellentesque a diam sit amet mi ullamcorper vehicula. Integer adipiscing risus a sem. Nullam quis massa sit amet nibh viverra malesuada. Nunc sem lacus, accumsan quis, faucibus non, congue vel, arcu. Ut scelerisque hendrerit tellus. Integer sagittis. Vivamus a mauris eget arcu gravida tristique. Nunc iaculis mi in ante. Vivamus imperdiet nibh feugiat est.
								</p>
								<h2>Tags</h2>
								<ul class="tags-list">
									<li><a href="#">World</a></li>
									<li><a href="#">Politic</a></li>
									<li><a href="#">Travel</a></li>
									<li><a href="#">Photos</a></li>
								</ul>
							</div>
						</div>
						<!-- End single-post -->

						<!-- Advertisement -->
						<div class="advertisement">
							<a href="#"><img src="upload/addsense/620x80grey.jpg" alt=""></a>
						</div>
						<!-- End Advertisement -->
						
						<!-- Posts-block -->
						<div class="posts-block featured-box">
							<div class="title-section">
								<h1>You Might also Like</h1>
							</div>

							<div class="owl-wrapper">
								<div class="owl-carousel" data-num="3">
								
									<div class="item">
										<div class="news-post standart-post">
											<div class="post-image">
												<a href="single-post">
													<img src="upload/blog/s24.jpg" alt="">
												</a>
												<a href="#" class="category">Football</a>
											</div>
											<h2><a href="single-post.html">Autumn wear ...</a></h2>
										</div>
									</div>
								
									<div class="item">
										<div class="news-post standart-post">
											<div class="post-image">
												<a href="single-post">
													<img src="upload/blog/s25.jpg" alt="">
												</a>
												<a href="#" class="category">Football</a>
											</div>
											<h2><a href="single-post.html">Travelling is part of our life</a></h2>
										</div>
									</div>
								
									<div class="item">
										<div class="news-post standart-post">
											<div class="post-image">
												<a href="single-post">
													<img src="upload/blog/s26.jpg" alt="">
												</a>
												<a href="#" class="category category-food">Winter Sports</a>
											</div>
											<h2><a href="single-post.html">Skiing is most popular sport in winter</a></h2>
										</div>
									</div>
								
									<div class="item">
										<div class="news-post standart-post">
											<div class="post-image">
												<a href="single-post">
													<img src="upload/blog/s14.jpg" alt="">
												</a>
												<a href="#" class="category">Basketball</a>
											</div>
											<h2><a href="single-post.html">New alternatives are more productive</a></h2>
										</div>
									</div>

								</div>
							</div>
							
						</div>
						<!-- End Posts-block -->

						<!-- author-profile -->
						<div class="author-profile">
							<div class="author-box">
								<img alt="" src="upload/users/avatar6.jpg">
								<div class="author-content">
									<h4>Helena Doe <a href="#">14 posts</a></h4>
									<p>Suspendisse mauris. Fusce accumsan mollis eros. Pellentesque a diam sit amet mi ullamcorper vehicula. Integer adipiscing risus a sem. Nullam. </p>
									<ul class="author-social">
										<li><a href="#" class="facebook"><i class="fa fa-facebook"></i></a></li>
										<li><a href="#" class="google"><i class="fa fa-google-plus"></i></a></li>
										<li><a href="#" class="twitter"><i class="fa fa-twitter"></i></a></li>
										<li><a href="#" class="instagram"><i class="fa fa-instagram"></i></a></li>
										<li><a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a></li>
									</ul>
								</div>
							</div>
						</div>
						<!-- End author-profile -->

						<!-- comment area box -->
						<div class="comment-area-box">
							<div class="title-section">
								<h1><span>5 Comments</span></h1>
							</div>
							<ul class="comment-tree">
								<li>
									<div class="comment-box">
										<img alt="" src="upload/users/avatar3.jpg">
										<div class="comment-content">
											<h4>Fiona Herrerez <a href="#"><i class="fa fa-comment-o"></i>Reply</a></h4>
											<span><i class="fa fa-clock-o"></i>27 Jann 2018 at 8:57 pm</span>
											<p>Suspendisse mauris. Fusce accumsan mollis eros. Pellentesque a diam sit amet mi ullamcorper vehicula. Integer adipiscing risus a sem. Nullam. </p>
										</div>
									</div>
								</li>
								<li>
									<div class="comment-box">
										<img alt="" src="upload/users/avatar1.jpg">
										<div class="comment-content">
											<h4>John Doe <a href="#"><i class="fa fa-comment-o"></i>Reply</a></h4>
											<span><i class="fa fa-clock-o"></i>27 Jann 2018 at 8:57 pm</span>
											<p>Fusce accumsan mollis eros. Pellentesque a diam sit amet mi ullamcorper vehicula. Integer adipiscing risus a sem. Nullam quis massa sit amet nibh viverra malesuada. Nunc sem lacus, accumsan quis, faucibus non, congue vel, arcu. Ut scelerisque hendrerit tellus. Integer sagittis. </p>
										</div>
									</div>
									<ul class="depth">
										<li>
											<div class="comment-box">
												<img alt="" src="upload/users/avatar2.jpg">
												<div class="comment-content">
													<h4>John Doe <a href="#"><i class="fa fa-comment-o"></i>Reply</a></h4>
													<span><i class="fa fa-clock-o"></i>27 Jann 2018 at 8:57 pm</span>
													<p>CNullam quis massa sit amet nibh viverra malesuada. Nunc sem lacus, accumsan quis, faucibus non. </p>
												</div>
											</div>
										</li>
									</ul>
								</li>

								<li>
									<div class="comment-box">
										<img alt="" src="upload/users/avatar4.jpg">
										<div class="comment-content">
											<h4>John Doe <a href="#"><i class="fa fa-comment-o"></i>Reply</a></h4>
											<span><i class="fa fa-clock-o"></i>27 Jann 2018 at 8:57 pm</span>
											<p>Fusce accumsan mollis eros. Pellentesque a diam sit amet mi ullamcorper vehicula. Integer adipiscing risus a sem. Nullam quis massa sit amet nibh viverra malesuada. Nunc sem lacus, accumsan quis, faucibus non, congue vel, arcu. Ut scelerisque hendrerit tellus. Integer sagittis. </p>
										</div>
									</div>
								</li>

								<li> 
									<div class="comment-box">
										<img alt="" src="upload/users/avatar5.jpg">
										<div class="comment-content">
											<h4>Maria Lilly <a href="#"><i class="fa fa-comment-o"></i>Reply</a></h4>
											<span><i class="fa fa-clock-o"></i>27 Jann 2018 at 8:57 pm</span>
											<p>Nullam quis massa sit amet nibh viverra malesuada. Nunc sem lacus, accumsan quis, faucibus non, congue vel. </p>
										</div>
									</div>
								</li>

							</ul>
						</div>
						<!-- End comment area box -->

						<!-- contact form box -->
						<div class="contact-form-box">
							<div class="title-section">
								<h1><span>Leave a Comment</span> <span class="email-not-published">Your email address will not be published.</span></h1>
							</div>
							<form id="comment-form">
								<div class="row">
									<div class="col-md-6">
										<label for="name">Name*</label>
										<input id="name" name="name" type="text">
									</div>
									<div class="col-md-6">
										<label for="mail">E-mail*</label>
										<input id="mail" name="mail" type="text">
									</div>
								</div>
								<label for="website">Website</label>
								<input id="website" name="website" type="text">
								<label for="comment">Comment*</label>
								<textarea id="comment" name="comment"></textarea>
								<button type="submit" id="submit-contact">
									<i class="fa fa-comment"></i> Post Comment
								</button>
							</form>
						</div>
						<!-- End contact form box -->

					</div>

					<div class="col-lg-4 sidebar-sticky">
						
						<!-- Sidebar -->
						<div class="sidebar theiaStickySidebar">
							<div class="widget social-widget">
								<h1>Stay Connected </h1>
								<p>Do you want to be informed everytime with our latest news?</p>
								<ul class="social-share">
									<li>
										<a href="#" class="rss">
											<i class="fa fa-rss"></i>
											<span>345</span>
										</a>
									</li>
									<li>
										<a href="#" class="facebook">
											<i class="fa fa-facebook"></i>
											<span>3,460</span>
										</a>
									</li>
									<li>
										<a href="#" class="twitter">
											<i class="fa fa-twitter"></i>
											<span>5,600</span>
										</a>
									</li>
									<li>
										<a href="#" class="google">
											<i class="fa fa-google-plus"></i>
											<span>659</span>
										</a>
									</li>
								</ul>
							</div>

							<div class="widget slider-widget">
								<h1>Featured Posts</h1>
								<div class="flexslider">
									<ul class="slides">
										<li>
											<img alt="" src="upload/blog/s23.jpg" />
											<div class="slider-caption">
												<a href="#" class="category">Winter sports</a>
												<h2><a href="single-post.html">Ski Alpine final in Austria</a></h2>
												<ul class="post-tags">
													<li><i class="lnr lnr-user"></i>by <a href="#">John Doe</a></li>
													<li><a href="#"><i class="lnr lnr-book"></i><span>23 comments</span></a></li>
												</ul>
											</div>
										</li>
										<li>
											<img alt="" src="upload/blog/s24.jpg" />
											<div class="slider-caption">
												<a href="#" class="category">Football</a>
												<h2><a href="single-post.html">World Cup in Germany</a></h2>
												<ul class="post-tags">
													<li><i class="lnr lnr-user"></i>by <a href="#">John Doe</a></li>
													<li><a href="#"><i class="lnr lnr-book"></i><span>23 comments</span></a></li>
												</ul>
											</div>
										</li>
										<li>
											<img alt="" src="upload/blog/s25.jpg" />
											<div class="slider-caption">
												<a href="#" class="category">Football</a>
												<h2><a href="single-post.html">El classico in Nou Camp</a></h2>
												<ul class="post-tags">
													<li><i class="lnr lnr-user"></i>by <a href="#">John Doe</a></li>
													<li><a href="#"><i class="lnr lnr-book"></i><span>23 comments</span></a></li>
												</ul>
											</div>
										</li>
									</ul>
								</div>

								<ul class="small-posts">
									<li>
										<a href="single-post.html">
											<img src="upload/blog/th5.jpg" alt="">
										</a>
										<div class="post-cont">
											<h2><a href="single-post.html">Commentary: Spotify’s Unusual IPO Came at the Perfect Time</a></h2>
											<ul class="post-tags">
												<li>by <a href="#">John Doe</a></li>
											</ul>
										</div>
									</li>
									<li>
										<a href="single-post.html">
											<img src="upload/blog/th7.jpg" alt="">
										</a>
										<div class="post-cont">
											<h2><a href="single-post.html">5 Key Things to Know About Haiti After Donald Trump's Insult</a></h2>
											<ul class="post-tags">
												<li>by <a href="#">John Doe</a></li>
											</ul>
										</div>
									</li>
									<li>
										<a href="single-post.html">
											<img src="upload/blog/th9.jpg" alt="">
										</a>
										<div class="post-cont">
											<h2><a href="single-post.html">These Are All the Sam's Club Locations That Are Closing</a></h2>
											<ul class="post-tags">
												<li>by <a href="#">John Doe</a></li>
											</ul>
										</div>
									</li>
								</ul>
							</div>

							<div class="advertisement">
								<a href="#"><img src="upload/addsense/300x250.jpg" alt=""></a>
							</div>

							<div class="widget tags-widget">
								<h1>Tags</h1>
								<ul class="tags-list">
									<li><a href="#">Football</a></li>
									<li><a href="#">Basketball</a></li>
									<li><a href="#">Tennis</a></li>
									<li><a href="#">Athletic</a></li>
									<li><a href="#">Winter Sports</a></li>
									<li><a href="#">Handball</a></li>
									<li><a href="#">Rugby</a></li>
								</ul>
							</div>

						</div>

					</div>
				</div>

			</div>
		</section>
		<!-- End content section -->

		<!-- footer 
			================================================== -->
		<footer>
			<div class="container">

				<div class="up-footer">
					<div class="row">

						<div class="col-lg-3 col-md-6">
							<div class="footer-widget text-widget">
								<h1><a href="index.html"><img src="{{ asset('images/logo.png') }}" alt=""></a></h1>
								<p>Duis aute irure dolor in reprehenderit in voluptate velit esse
								cillum dolore eu fugiat nulla pariatur. Excepteur occaecat cupidatat non
								proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
								<ul class="social-icons">
									<li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
									<li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
									<li><a class="google" href="#"><i class="fa fa-google-plus"></i></a></li>
									<li><a class="linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>
									<li><a class="instagram" href="#"><i class="fa fa-instagram"></i></a></li>
								</ul>
							</div>
						</div>

						<div class="col-lg-3 col-md-6">
							<div class="footer-widget popular-widget">
								<h1>Popular News</h1>
								<ul class="small-posts">
									<li>
										<a href="single-post.html">
											<img src="upload/blog/th3.jpg" alt="">
										</a>
										<div class="post-cont">
											<h2><a href="single-post.html">New alternatives are more productive</a></h2>
											<ul class="post-tags">
												<li>by <a href="#">Author</a></li>
											</ul>
										</div>
									</li>
									<li>
										<a href="single-post.html">
											<img src="upload/blog/th4.jpg" alt="">
										</a>
										<div class="post-cont">
											<h2><a href="single-post.html">Vue js new javascript Framework</a></h2>
											<ul class="post-tags">
												<li>by <a href="#">Besim Dauti</a></li>
											</ul>
										</div>
									</li>
									<li>
										<a href="single-post.html">
											<img src="upload/blog/th5.jpg" alt="">
										</a>
										<div class="post-cont">
											<h2><a href="single-post.html">Eating traditional food is more healthy</a></h2>
											<ul class="post-tags">
												<li>by <a href="#">Admin Mag</a></li>
											</ul>
										</div>
									</li>
								</ul>
							</div>
						</div>

						<div class="col-lg-3 col-md-6">
							<div class="footer-widget featured-widget">
								<h1>Featured Post</h1>
								<div class="news-post standart-post">
									<div class="post-image">
										<a href="single-post">
											<img src="upload/blog/s15.jpg" alt="">
										</a>
										<a href="#" class="category">Tennis</a>
									</div>
									<h2><a href="single-post.html">Visiting antic countries is John Doe hobby.</a></h2>
								</div>

							</div>
						</div>

						<div class="col-lg-3 col-md-6">
							<div class="footer-widget tags-widget">
								<h1>Tags</h1>
								<ul class="tags-list">
									<li><a href="#">Football</a></li>
									<li><a href="#">Basketball</a></li>
									<li><a href="#">Tennis</a></li>
									<li><a href="#">Athletic</a></li>
									<li><a href="#">Winter Sports</a></li>
									<li><a href="#">Handball</a></li>
									<li><a href="#">Rugby</a></li>
								</ul>
							</div>
						</div>

					</div>
				</div>

				<div class="down-footer">
					<ul class="list-footer">
						<li><a href="index.html">Home</a></li>
						<li><a href="about.html">About Us</a></li>
						<li><a href="privacy-policy.html">Privacy policy</a></li>
						<li><a href="contact.html">Contact</a></li>
					</ul>
					<p>&copy; Copyright By Nunforest 2017<a href="#" class="go-top"><i class="fa fa-caret-up" aria-hidden="true"></i></a></p>
				</div>

			</div>
		</footer>
		<!-- End footer -->

	</div>
	<!-- End Container -->



	<!-- Login Modal -->
	<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-body">
	        <div class="title-section">
	        	<h1>Login</h1>
	        </div>
			<form id="login-form">
				<p>Welcome! Login to your account.</p>
				<label for="username">Username or Email Address*</label>
				<input id="username" name="username" type="text">
				<label for="password">Password*</label>
				<input id="password" name="password" type="password">
				<button type="submit" id="submit-register">
					<i class="fa fa-paper-plane"></i> Login
				</button>
			</form>
	      	<p>Don't have account? <a href="register.html">Register Here</a></p>

	      </div>
	    </div>
	  </div>
	</div>
	<!-- End Login Modal -->

	<script src="js/modernmag-plugins.min.js"></script>
	<script src="js/popper.js"></script>
	<script src="js/bootstrap.min.js"></script>
    <script src="http://maps.google.com/maps/api/js?key=AIzaSyCiqrIen8rWQrvJsu-7f4rOta0fmI5r2SI&amp;sensor=false&amp;language=en"></script>
	<script src="js/gmap3.min.js"></script>
	<script src="js/script.js"></script>
	
</body>
</html>
