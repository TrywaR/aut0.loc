        
        <header>
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/">aut0</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="/">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/users/">Users</a>
                            </li>
                        </ul>
                        <div class="d-flex">
                            <ul style="margin: 0; padding-right: 1rem;">
                                <?
                                $oSession = $_SESSION['session'];
                                if( empty($oSession->user) ){?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/login/">Login</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/registration/">Registration</a>
                                    </li>
                                <?}else{?>
                                    <li class="nav-item">
                                        <small>
                                            <?=$oSession->user->name?>
                                        </small>
                                        <br>
                                        <a class="nav-link" href="/" id="user_exit">Exit</a>
                                    </li>
                                <?}?>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            