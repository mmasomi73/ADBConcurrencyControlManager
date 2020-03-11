<nav class="pcoded-navbar">
    <div class="pcoded-inner-navbar main-menu">
        <div class="">
            <div class="main-menu-header">

                <div class="user-details">
                    <p id="more-details">
                        <i class="feather icon-chevron-down m-r-10"></i>

                    </p>
                </div>
            </div>
        </div>
        <div class="pcoded-navigation-label">اصلی</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="@if(\Route::currentRouteName() == 'index.panel') active @endif">
                <a href="{{route('index.panel')}}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                    <span class="pcoded-mtext">داشبورد</span>
                </a>
            </li>
        </ul>
        <div class="pcoded-navigation-label">کاربردی</div>
        <ul class="pcoded-item pcoded-left-item">
            <li class="@if(\Route::currentRouteName() == 'schedule.index') active @endif">
                <a href="{{route('schedule.index')}}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                    <span class="pcoded-mtext">اسکجول‌ها</span>
                </a>
            </li>
            <li class="@if(\Route::currentRouteName() == 'user.index' or \Route::currentRouteName() == 'user.view') active @endif">
                <a href="{{route('user.index')}}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                    <span class="pcoded-mtext">دانشجویان</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
