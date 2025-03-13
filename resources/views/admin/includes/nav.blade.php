<style>
    .product-item{
      display: flex;
      align-items: center;
      justify-content: flex-start;
      gap: 15px;
      transition: all 0.3s ease;
      padding-right: 5px;
    }
    .product-item .image{
      display: flex;
      align-items: center;
      justify-content: center;
      width: 50px;
      height: 50px;
      gap: 10px;
      flex-shrink: 0;
      padding: 5px;
      margin: 10px;
      border-radius: 30px;
      background: #Eff4f8;
      overflow: hidden;
      box-shadow: 0 0 7px #4ECDC4, 0 0 17px #349090;
      animation: glow-border 1.5s infinite alternate;
    }
    .product-item .image .imged{
      border-radius: 30px;
    }
    #box-content-search li{
      list-style: none;
    }
    #box-content-search .product-item{
      margin-bottom: 10px;
    }
</style>

<div class="header-dashboard">
    <div class="wrap">
        <div class="header-left">
            <a href="index-2.html">
                <img class="" id="logo_header_mobile" alt="" src="images/logo/logo.png"
                    data-light="images/logo/logo.png" data-dark="images/logo/logo.png"
                    data-width="154px" data-height="52px" data-retina="images/logo/logo.png">
            </a>
            <div class="button-show-hide">
                <i class="icon-menu-left"></i>
            </div>


            <form class="form-search flex-grow">
                <fieldset class="name">
                    <input type="text" placeholder="Search here..." class="show-search" name="name" id="search-input" tabindex="2" aria-required="true" autocomplete="off" >
                </fieldset>
                <div class="button-submit">
                    <button class="" type="submit"><i class="icon-search"></i></button>
                </div>
                <div class="box-content-search">
                    <ul id="box-content-search"></ul>
                  </div>
            </form>

        </div>
        <div class="header-grid">

            <div class="popup-wrap message type-header">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button"
                        id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="header-item">
                            <span class="text-tiny">1</span>
                            <i class="icon-bell"></i>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end has-content"
                        aria-labelledby="dropdownMenuButton2">
                        <li>
                            <h6>Notifications</h6>
                        </li>
                        <li>
                            <div class="message-item item-1">
                                <div class="image">
                                    <i class="icon-noti-1"></i>
                                </div>
                                <div>
                                    <div class="body-title-2">Discount available</div>
                                    <div class="text-tiny">Morbi sapien massa, ultricies at rhoncus
                                        at, ullamcorper nec diam</div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="message-item item-2">
                                <div class="image">
                                    <i class="icon-noti-2"></i>
                                </div>
                                <div>
                                    <div class="body-title-2">Account has been verified</div>
                                    <div class="text-tiny">Mauris libero ex, iaculis vitae rhoncus
                                        et</div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="message-item item-3">
                                <div class="image">
                                    <i class="icon-noti-3"></i>
                                </div>
                                <div>
                                    <div class="body-title-2">Order shipped successfully</div>
                                    <div class="text-tiny">Integer aliquam eros nec sollicitudin
                                        sollicitudin</div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="message-item item-4">
                                <div class="image">
                                    <i class="icon-noti-4"></i>
                                </div>
                                <div>
                                    <div class="body-title-2">Order pending: <span>ID 305830</span>
                                    </div>
                                    <div class="text-tiny">Ultricies at rhoncus at ullamcorper</div>
                                </div>
                            </div>
                        </li>
                        <li><a href="#" class="tf-button w-full">View all</a></li>
                    </ul>
                </div>
            </div>

            <div class="popup-wrap user type-header">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button"
                        id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="header-user wg-user">
                            <span class="image">
                                <img src="images/avatar/user-1.png" alt="">
                            </span>
                            <span class="flex flex-column">
                                <span class="body-title mb-2">Thor Sophanith</span>
                                <span class="text-tiny">Admin</span>
                            </span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end has-content"
                        aria-labelledby="dropdownMenuButton3">
                        <li>
                            <a href="#" class="user-item">
                                <div class="icon">
                                    <i class="icon-user"></i>
                                </div>
                                <div class="body-title-2">Account</div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="user-item">
                                <div class="icon">
                                    <i class="icon-mail"></i>
                                </div>
                                <div class="body-title-2">Inbox</div>
                                <div class="number">27</div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('home') }}" class="user-item">
                                <div class="icon">
                                    <i class="icon-file-text"></i>
                                </div>
                                <div class="body-title-2">My Website</div>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="user-item">
                                <div class="icon">
                                    <i class="icon-headphones"></i>
                                </div>
                                <div class="body-title-2">Support</div>
                            </a>
                        </li>
                        <li>
                        <form method="POST" action="{{ route( 'logout' )}}" id="logout-form">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="user-item">
                                <div class="icon"><i class="icon-log-out"></i></div>
                                <div class="body-title-2">Log out</div>
                            </a>
                        </form>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>