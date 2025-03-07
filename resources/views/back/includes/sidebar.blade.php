<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!-- User details -->
        <div class="user-profile text-center mt-3">
            <div class="">
                <img src="{{ asset('back/assets/images/logoblog.svg') }}" width="80" alt="">
            </div>
            <div class="mt-3">
                <h4 class="font-size-16 mb-1">{{ auth()->user()->name }}</h4>
                <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i>
                    Online</span>
            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="№" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Ana Səhifə</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-settings-3-line"></i>
                        <span>Tənzimləmələr</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                    <li>
                            <a href="{{ route('back.pages.translation-manage.index') }}">
                                <i class="ri-translate"></i>
                                <span>Tərcümələr</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.seo.index') }}">
                                <i class="ri-earth-line"></i>
                                <span>SEO</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.logos.index') }}">
                                <i class="ri-file-line"></i>
                                <span>Logo</span>
                            </a>
                        </li>


                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-share-line"></i>
                        <span>Sosial Media</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                       
                    <li>
                            <a href="{{ route('back.pages.social.index') }}">
                                <i class="ri-messenger-line"></i>
                                <span>Sosial Media</span>
                            </a>
                        </li>   

                        <li>
                        <a href="{{ route('back.pages.socialshare.index') }}">
                            <i class="ri-share-line"></i>
                            <span>Sosial Share</span>
                        </a>
                    </li>  

                    <li>
                        <a href="{{ route('back.pages.socialfooter.index') }}">
                            <i class="ri-mail-open-line"></i>
                            <span>Sosial Footer</span>
                        </a>
                    </li>  

                        
   
                    </ul>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
