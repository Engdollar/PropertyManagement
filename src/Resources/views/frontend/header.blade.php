
 <!-- header start here -->
 <header class="site-header">
    <div class="main-navigationbar sticky_header" id="header-sticky">
        <div class="container">
            <div class="header-col">
                <div class="navigationbar-row flex align-center justify-between">
                    <div class="logo-col">
                        <h1>
                            <a href="{{ route('property.listing',$slug) }}" tabindex="0">
                                <img src="{{ check_file($logo) ? get_file($logo) : get_file('uploads/logo/logo.png') }}{{ '?' . time() }}"
                                alt="logo" class="logo logo-lg" style="width: 125px;" loading="lazy" />

                            </a>
                        </h1>
                    </div>
                    <div class="language-header has-item">

                        <a href="javascript:void;">
                            <span class="select">{{Str::upper($currantLang)}}</span>
                        </a>

                        <div class="menu-dropdown">
                            <ul>
                                @foreach($languages as $key => $language)
                                <li>
                                    <a href="{{ route('property.change.languagestore',['slug' => $slug,'lang' => $key]) }}" class=" dropdown-item  @if($key == $currantLang) text-primary @endif">
                                        <span>{{$language}}</span>
                                    </a>

                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header end here -->
