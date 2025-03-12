<?php
require_once('files/header.php');
?>

<div class="container py-4 py-lg-5 my-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow">
                <form action="login-logic.php" method="post">
                    <div class="card-body">
                        <h2 class="h4 mb-1">Bejelentkezés</h2>
                        <div class="py-3">
                            <h3 class="d-inline-block align-middle fs-base fw-medium mb-2 me-2">Közösségi fiókkal:</h3>
                            <div class="d-inline-block align-middle">
                                <a class="btn-social bs-google me-2 mb-2" href="#" data-bs-toggle="tooltip" title="Bejelentkezés Google-lal"><i class="ci-google"></i></a>
                                <a class="btn-social bs-facebook me-2 mb-2" href="#" data-bs-toggle="tooltip" title="Bejelentkezés Facebook-kal"><i class="ci-facebook"></i></a>
                                <a class="btn-social bs-twitter me-2 mb-2" href="#" data-bs-toggle="tooltip" title="Bejelentkezés Twitter-rel"><i class="ci-twitter"></i></a>
                            </div>
                        </div>
                        <hr>
                        <h3 class="fs-base pt-4 pb-2">Vagy az alábbi űrlap használatával</h3>
                        <form class="needs-validation" novalidate>
                            <div class="input-group mb-3">
                                <i class="ci-mail position-absolute top-50 translate-middle-y text-muted fs-base ms-3"></i>
                                <input class="form-control rounded-start" name="email" type="email" placeholder="Email" required>
                            </div>
                            <div class="input-group mb-3">
                                <i class="ci-locked position-absolute top-50 translate-middle-y text-muted fs-base ms-3"></i>
                                <div class="password-toggle w-100">
                                    <input name="password" class="form-control" type="password" placeholder="Jelszó" required>
                                    <label class="password-toggle-btn" aria-label="Jelszó megjelenítése/elrejtése">
                                        <input class="password-toggle-check" type="checkbox"><span class="password-toggle-indicator"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked id="remember_me">
                                    <label class="form-check-label" for="remember_me">Emlékezz rám</label>
                                </div>
                                <a class="nav-link-inline fs-sm" href="login.php">Elfelejtette a jelszavát?</a>
                            </div>
                            <hr class="mt-4">
                            <div class="text-end pt-4">
                                <button class="btn btn-primary" type="submit"><i class="ci-sign-in me-2 ms-n21"></i>Bejelentkezés</button>
                            </div>
                        </form>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Regisztrációs űrlap -->
        <div class="col-md-6 pt-4 mt-3 mt-md-0">
            <h2 class="h4 mb-3">Még nincs fiókja? Regisztráljon</h2>
            <p class="fs-sm text-muted mb-4">A regisztráció kevesebb mint egy percet vesz igénybe, de teljes ellenőrzést ad a rendelései felett.</p>
            <form method="post" action="register-logic.php" class="needs-validation" novalidate>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h3 class="fs-base pt-4 pb-2">Regisztráljon az alábbiakban</h3>
                        <div class="row gx-4 gy-3">
                            <div class="col-sm-6">
                                <label class="form-label" for="reg-fn">Keresztnév</label>
                                <input class="form-control" name="first_name" type="text" required id="reg-fn">
                                <div class="invalid-feedback">Kérem, adja meg a keresztnevét!</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="reg-ln">Vezetéknév</label>
                                <input class="form-control" name="last_name" type="text" required id="reg-ln">
                                <div class="invalid-feedback">Kérem, adja meg a vezetéknevét!</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="reg-email">E-mail cím</label>
                                <input class="form-control" name="email" type="email" required id="reg-email">
                                <div class="invalid-feedback">Kérem, adjon meg egy érvényes e-mail címet!</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="reg-phone">Telefonszám</label>
                                <input class="form-control" name="phone_number" type="text" required id="reg-phone">
                                <div class="invalid-feedback">Kérem, adja meg a telefonszámát!</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="reg-password">Jelszó</label>
                                <input class="form-control" name="password" type="password" required id="reg-password">
                                <div class="invalid-feedback">Kérem, adjon meg egy jelszót!</div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label" for="reg-password-confirm">Jelszó megerősítése</label>
                                <input class="form-control" name="password_1" type="password" required id="reg-password-confirm">
                                <div class="invalid-feedback">A jelszavak nem egyeznek!</div>
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-primary" type="submit"><i class="ci-user me-2 ms-n1"></i>Regisztráció</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once('files/footer.php');
?>
