<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu" style="background-color: rgb(34, 33, 33);">

    <div data-simplebar class="h-100">

        <!-- User details -->
        <div class="user-profile text-center mt-3">
           
            <div class="mt-3">
                <h4 class="font-size-16 mb-1" style="color: white;">
                    {{ auth()->guard('admin')->user()->name ?? 'Administrator' }}
                </h4>
                <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i>
                    Online</span>
            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu" >
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu" style="color: white; " >
                <li class="menu-title" style="color: white; " >Menu</li>

                <li>
                    <a href="{{ route('admin.dashboard') }}" class="waves-effect">
                        <i class="ri-dashboard-line" style="color: white;"></i>
                        <span style="color: white;">Ana Səhifə</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" style="background: #111; border-left: 3px solid #fff;">
                        <i class="ri-settings-3-line" style="color: white;"></i>
                        <span style="color: white;">Tənzimləmələr</span>
                    </a>
                    <ul class="sub-menu" style="background: #111; border-left: 3px solid #fff;">
                        <li>
                            <a href="{{ route('back.pages.translation-manage.index') }}" 
                               style="color: white;">
                                    <i class="ri-translate" style="color: white;"></i> Tərcümələr
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.seo.index') }}" style="color: white;">
                                <i class="ri-earth-line" style="color: white;"></i>
                                <span >SEO</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.logos.index') }}" style="color: white;">
                                <i class="ri-file-line" style="color: white;"></i>
                                <span >Logo</span>
                            </a>
                        </li>


                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" style="background: #111; border-left: 3px solid #fff;">
                        <i class="ri-share-line" style="color: white;"></i>
                        <span style="color: white;">Sosial Media</span>
                    </a>
                    <ul class="sub-menu" style="background: #111; border-left: 3px solid #fff;">
                       
                    <li>
                            <a href="{{ route('back.pages.social.index') }}" style="color: white;">
                                <i class="ri-messenger-line" style="color: white;"></i>
                                <span >Sosial Media</span>
                            </a>
                        </li>   

                        <li>
                        <a href="{{ route('back.pages.socialshare.index') }}" style="color: white;">
                            <i class="ri-share-line" style="color: white;"></i>
                            <span >Sosial Share</span>
                        </a>
                    </li>  

                    <li>
                        <a href="{{ route('back.pages.socialfooter.index') }}" style="color: white;">
                            <i class="ri-mail-open-line" style="color: white;"></i>
                            <span >Sosial Footer</span>
                        </a>
                    </li>  

                        
   
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" style="background: #111; border-left: 3px solid #fff;">
                        <i class="ri-shopping-cart-line" style="color: white;"></i>
                        <span style="color: white;">Məhsul İdarəetməsi</span>
                    </a>
                    <ul class="sub-menu" style="background: #111; border-left: 3px solid #fff;">
                        <li>
                            <a href="{{ route('back.pages.products.index') }}" style="color: white;">
                                <i class="ri-shopping-bag-line" style="color: white;"></i>
                                <span>Məhsullar</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.product_colors.index') }}" style="color: white;">
                                <i class="ri-palette-line" style="color: white;"></i>
                                <span>Rənglər</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.product_sizes.index') }}" style="color: white;">
                                <i class="ri-ruler-line" style="color: white;"></i>
                                <span>Ölçülər</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.product_images.index') }}" style="color: white;">
                                <i class="ri-image-line" style="color: white;"></i>
                                <span>Şəkillər</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.product_stocks.index') }}" style="color: white;">
                                <i class="ri-stack-line" style="color: white;"></i>
                                <span>Stoklar</span>
                            </a>
                        </li>
                    </ul>
                </li>
                

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" style="background: #111; border-left: 3px solid #fff;">
                        <i class="ri-folder-line" style="color: white;"></i>
                        <span style="color: white;">Kategori İdarəetməsi</span>
                    </a>
                    <ul class="sub-menu" style="background: #111; border-left: 3px solid #fff;">
                        <li>
                            <a href="{{ route('back.pages.categories.index') }}" style="color: white;">
                                <i class="ri-folder-line" style="color: white;"></i>
                                <span>Kategori</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.service-heroes.index') }}" style="color: white;">
                                <i class="ri-folder-line" style="color: white;"></i>
                                <span>Servis Hero</span>
                            </a>
                        </li>


                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" style="background: #111; border-left: 3px solid #fff;">
                        <i class="ri-home-line" style="color: white;"></i>
                        <span style="color: white;">Ana Səhifə</span>
                    </a>
                    <ul class="sub-menu" style="background: #111; border-left: 3px solid #fff;">
                        <li>
                            <a href="{{ route('back.pages.home-heroes.index') }}" style="color: white;">
                                <i class="ri-home-line" style="color: white;"></i>
                                <span>Home Hero</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.partners.index') }}" style="color: white;">
                                <i class="ri-home-line" style="color: white;"></i>
                                <span>Partnyorlar</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.home-follows.index') }}" style="color: white;">
                                <i class="ri-home-line" style="color: white;"></i>
                                <span>Sosial Hesablar</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.home-cart-sections.index') }}" style="color: white;">
                                <i class="ri-home-line" style="color: white;"></i>
                                <span>Kartlar</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.home-featured-boxes.index') }}" style="color: white;">
                                <i class="ri-home-line" style="color: white;"></i>
                                <span>Featured Boxlar</span>
                            </a>
                        </li>
                       
                    </ul>

                    <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" style="background: #111; border-left: 3px solid #fff;">
                        <i class="ri-information-line" style="color: white;"></i>
                        <span style="color: white;">Haqqımızda</span>
                    </a>
                    
                    <ul class="sub-menu" style="background: #111; border-left: 3px solid #fff;">
                        <li>
                            <a href="{{ route('back.pages.about.index') }}" style="color: white;">
                                <i class="ri-information-line" style="color: white;"></i>
                                <span>Haqqımızda Hero</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('back.pages.about-cart-sections.index') }}" style="color: white;">
                                <i class="ri-information-line" style="color: white;"></i>
                                <span>Haqqımızda Kart</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.about-text-sections.index') }}" style="color: white;">
                                <i class="ri-information-line" style="color: white;"></i>
                                <span>Haqqımızda Mətin</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.about-center-cart.index') }}" style="color: white;">
                                <i class="ri-information-line" style="color: white;"></i>
                                <span>Center Kart</span>
                            </a>
                        </a>

                        <li>
                            <a href="{{ route('back.pages.about-featured-boxes.index') }}" style="color: white;">
                                <i class="ri-information-line" style="color: white;"></i>
                                <span>Featured Boxlar</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" style="background: #111; border-left: 3px solid #fff;">
                        <i class="ri-phone-line" style="color: white;"></i>
                        <span style="color: white;">Əlaqə</span>
                    </a>
                    
                    <ul class="sub-menu" style="background: #111; border-left: 3px solid #fff;">
                        <li>
                            <a href="{{ route('back.pages.contact.index') }}" style="color: white;">
                                <i class="ri-phone-line" style="color: white;"></i>
                                <span>Əlaqə</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.contact-heroes.index') }}" style="color: white;">
                                <i class="ri-phone-line" style="color: white;"></i>
                                <span>Əlaqə Hero</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('back.pages.contact-titles.index') }}" style="color: white;">
                                <i class="ri-phone-line" style="color: white;"></i>
                                <span>Əlaqə Başlıqları</span>
                            </a>
                        </li>

                        
                    </ul>

                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" style="background: #111; border-left: 3px solid #fff;">
                        <i class="ri-image-line" style="color: white;"></i>
                        <span style="color: white;">Partner</span>
                    </a>
                    <ul class="sub-menu" style="background: #111; border-left: 3px solid #fff;">
                        <li>
                            <a href="{{ route('back.pages.partner-banners.index') }}" style="color: white;">
                                <i class="ri-image-line" style="color: white;"></i>
                                <span>Partner</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.partner-heroes.index') }}" style="color: white;">
                                <i class="ri-image-line" style="color: white;"></i>
                                <span>Partner-Hero</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" style="background: #111; border-left: 3px solid #fff;">
                        <i class="ri-file-text-line" style="color: white;"></i>
                        <span style="color: white;">Product Bannerlar</span>
                    </a>
                    <ul class="sub-menu" style="background: #111; border-left: 3px solid #fff;">
                        <li>
                            <a href="{{ route('back.pages.product-banner.index') }}" style="color: white;">
                                <i class="ri-file-text-line" style="color: white;"></i>
                                <span>Product Bannerlar</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" style="background: #111; border-left: 3px solid #fff;">
                        <i class="ri-file-text-line" style="color: white;"></i>
                        <span style="color: white;">Bloq</span>
                    </a>
                    <ul class="sub-menu" style="background: #111; border-left: 3px solid #fff;">
                        <li>
                            <a href="{{ route('back.pages.blog.index') }}" style="color: white;">
                                <i class="ri-file-text-line" style="color: white;"></i>
                                <span>Bloq</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.blog-banner.index') }}" style="color: white;">
                                <i class="ri-image-line" style="color: white;"></i>
                                <span>Bloq Banner</span>
                            </a>
                        </li>
                    </ul>
                </li>   

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" style="background: #111; border-left: 3px solid #fff;">
                        <i class="ri-user-line" style="color: white;"></i>
                        <span style="color: white;">İstifadəçi İdarəetməsi</span>
                    </a>
                    <ul class="sub-menu" style="background: #111; border-left: 3px solid #fff;">
                        <li>
                            <a href="{{ route('back.pages.users.index') }}" style="color: white;">
                                <i class="ri-user-line" style="color: white;"></i>
                                <span>İstifadəçi İdarəetməsi</span>   
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.home-questions.index') }}" style="color: white;">
                                <i class="ri-question-line" style="color: white;"></i>
                                <span>Sual-Cavab</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" style="background: #111; border-left: 3px solid #fff;">
                        <i class="ri-file-text-line" style="color: white;"></i>
                        <span style="color: white;">Sifarişler</span>
                    </a>
                    <ul class="sub-menu" style="background: #111; border-left: 3px solid #fff;">
                        <li>    
                            <a href="{{ route('back.pages.orders.index') }}" style="color: white;">
                                <i class="ri-file-text-line" style="color: white;"></i>
                                <span>Sifarişler</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.carts.index') }}" style="color: white;">
                                <i class="ri-file-text-line" style="color: white;"></i>
                                <span>Sepetler</span>
                            </a>
                        </li>
                    </ul>
                </li>
                    

            </ul>
        </div>
     
    </div>
</div>

