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
                        <span>Ana Səhifə</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" style="background: #111; border-left: 3px solid #fff;">
                        <i class="ri-settings-3-line" style="color: white;"></i>
                        <span>Tənzimləmələr</span>
                    </a>
                    <ul class="sub-menu" style="background: #111; border-left: 3px solid #fff;">
                        <li>
                            <a href="{{ route('back.pages.translation-manage.index') }}" 
                               style="color: #ccc;">
                                <i class="ri-translate"></i> Tərcümələr
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.seo.index') }}" style="color: #ccc;">
                                <i class="ri-earth-line"></i>
                                <span>SEO</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('back.pages.logos.index') }}" style="color: #ccc;">
                                <i class="ri-file-line"></i>
                                <span>Logo</span>
                            </a>
                        </li>


                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" style="background: #111; border-left: 3px solid #fff;">
                        <i class="ri-share-line" style="color: white;"></i>
                        <span>Sosial Media</span>
                    </a>
                    <ul class="sub-menu" style="background: #111; border-left: 3px solid #fff;">
                       
                    <li>
                            <a href="{{ route('back.pages.social.index') }}" style="color: #ccc;">
                                <i class="ri-messenger-line"></i>
                                <span>Sosial Media</span>
                            </a>
                        </li>   

                        <li>
                        <a href="{{ route('back.pages.socialshare.index') }}" style="color: #ccc;">
                            <i class="ri-share-line"></i>
                            <span>Sosial Share</span>
                        </a>
                    </li>  

                    <li>
                        <a href="{{ route('back.pages.socialfooter.index') }}" style="color: #ccc;">
                            <i class="ri-mail-open-line"></i>
                            <span>Sosial Footer</span>
                        </a>
                    </li>  

                        
   
                    </ul>
                </li>

              
                   
                    </ul>
                        
                </li>

              

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
