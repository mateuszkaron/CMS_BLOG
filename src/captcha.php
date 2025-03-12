<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Generowanie nowego numeru CAPTCHA
$captchaNumber = rand(1, 9);
$_SESSION['captcha_correct'] = $captchaNumber;

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div id="captcha">
    <h2 style="text-align:center;">FIND HUMAN!</h2>
    <div id="captcha-grid">
        <?php
        for ($i = 1; $i <= 9; $i++) {
            if ($i === $captchaNumber) {
                echo '<div id="captcha' . $i . '" class="captcha-icon" data-value="' . $i . '"><i class="fa-regular fa-user"></i></div>';
            } else {
                echo '<div id="captcha' . $i . '" class="captcha-icon" data-value="' . $i . '"><i class="fa-solid fa-robot"></i></div>';
            }
        }
        ?>
    </div>
    <input type="hidden" name="captcha_value" id="captcha_value">
</div>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        let captchaIcons = document.querySelectorAll('.captcha-icon');
        let selectedCaptcha = null;

        captchaIcons.forEach((icon) => {
            icon.addEventListener('click', function () {
                if (selectedCaptcha !== null) {
                    let previousIcon = document.querySelector('.captcha-icon.selected');
                    if (previousIcon) {
                        previousIcon.classList.remove('selected');
                    }
                }

                icon.classList.add('selected');
                selectedCaptcha = parseInt(icon.getAttribute('data-value'));
                document.getElementById("captcha_value").value = selectedCaptcha;
            });
        });
    });

</script>

<style>
    div#captcha {
        text-align: center;
        margin: 20px 0;
    }

    div#captcha-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-gap: 10px;
        justify-items: center;
    }

    .captcha-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ccc;
        border-radius: 50%;
        font-size: 24px;
        cursor: pointer;
    }

    .captcha-icon:hover {
        background-color: #f0f0f0;
    }

    .captcha-icon.selected {
        border-color: rgba(255, 165, 0,1);
    }

    .captcha-icon i {
        color: #555;
    }
</style>
