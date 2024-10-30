<?php

namespace Mint\MRM\Internal\Admin;

use MRM\Common\MrmCommon;

/**
 * SpecialOccasionBanner Class
 *
 * This class is responsible for displaying a special occasion banner in the WordPress admin.
 *
 * @package YourVendor\SpecialOccasionPlugin
 */
class SpecialOccasionBanner
{

    /**
     * The occasion identifier.
     *
     * @var string
     */
    private $occasion;

    /**
     * The start date and time for displaying the banner.
     *
     * @var int
     */
    private $start_date;

    /**
     * The end date and time for displaying the banner.
     *
     * @var int
     */
    private $end_date;

    /**
     * Constructor method for SpecialOccasionBanner class.
     *
     * @param string $occasion   The occasion identifier.
     * @param string $start_date The start date and time for displaying the banner.
     * @param string $end_date   The end date and time for displaying the banner.
     */
    public function __construct($occasion, $start_date, $end_date)
    {
        $this->occasion = $occasion;
        $this->start_date = strtotime($start_date);
        $this->end_date = strtotime($end_date);

        // Hook into the admin_notices action to display the banner
        add_action('admin_notices', array($this, 'display_banner'));

        // Add styles
        add_action('admin_head', array($this, 'add_styles'));
    }

    /**
     * Calculate time remaining until Halloween
     *
     * @return array Time remaining in days, hours, and minutes
     */
    function mint_get_halloween_countdown() {
        $halloween = strtotime('2024-10-21 23:59:59'); // Set this to the next Halloween
        $now = current_time('timestamp');
        $diff = $halloween - $now;

        return array(
            'days' => floor($diff / (60 * 60 * 24)),
            'hours' => floor(($diff % (60 * 60 * 24)) / (60 * 60)),
            'mins' => floor(($diff % (60 * 60)) / 60),
        );
    }


    /**
     * Displays the special occasion banner if the current date and time are within the specified range.
     */
    public function display_banner()
    {
        $screen = get_current_screen();
        $promotional_notice_pages = ['dashboard', 'plugins', 'toplevel_page_mrm-admin'];
        $current_date_time = current_time('timestamp');

        if (!in_array($screen->id, $promotional_notice_pages)) {
            return;
        }

        if (defined('MAIL_MINT_PRO_VERSION') || ($current_date_time < $this->start_date || $current_date_time > $this->end_date) || 'no' === get_option('_is_mint_hallowen_promotion_24') || MrmCommon::is_wpfnl_active() || 'no' === get_option('_is_wpfnl_hallowen_promotion_24')) {
            return;
        }

        // Calculate the time remaining in seconds
        $time_remaining = $this->end_date - $current_date_time;

?>

        <?php 
            $dir_url = MRM_DIR_URL . 'admin/assets/';
            $countdown = $this->mint_get_halloween_countdown();
        ?>

        <!-- Name: WordPress Anniversary Notification Banner -->
        <div class="<?php echo esc_attr($this->occasion); ?>-banner notice">
            <div class="mailmint-promotional-banner">
                <div class="mailmint-tb__notification">

                    <div class="banner-overflow">

                        <section class="mint-notification-counter default-notification" aria-labelledby="mint-halloween-offer-title">
                            <div class="mint-notification-counter__container">
                                <div class="mint-notification-counter__content">

                                    <figure class="mint-notification-counter__figure-logo">
                                        <img src="<?php echo esc_url(MRM_DIR_URL . 'admin/assets/images/halloween/halloween-default.webp '); ?>" alt="Halloween special offer banner" class="mint-notification-counter__img">
                                    </figure>

                                    <figure class="mint-notification-counter__figure-percentage">
                                        <img src="<?php echo esc_url(MRM_DIR_URL . 'admin/assets/images/halloween/percentage.png'); ?>" alt="Halloween special offer banner" class="mint-notification-counter__img">
                                    </figure>

                                    <div id="mint-halloween-countdown" class="mint-notification-counter__countdown" aria-live="polite">
                                        <h3 class="screen-reader-text"><?php echo __('Offer Countdown', 'mrm'); ?></h3>
                                        <ul class="mint-notification-counter__list">

                                             <?php foreach (['days', 'hours', 'mins'] as $unit): ?>
                                                <li class="mint-notification-counter__item ">
                                                    <span id="mint-halloween-<?php echo esc_attr($unit); ?>" class="mint-notification-counter__time">
                                                        <?php echo esc_html($countdown[$unit]); ?>
                                                    </span>
                                                    <span class="mint-notification-counter__label">
                                                        <?php echo esc_html($unit); ?>
                                                    </span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>

                                    <div class="mint-notification-counter__btn-area">
                                        <a href="<?php echo esc_url('https://getwpfunnels.com/pricing/?utm_source=website&utm_medium=mm-ui&utm_campaign=halloween24#mail-mint'); ?>" class="mint-notification-counter__btn" role="button">

                                        <span class="mint-btn-inner">
                                            <span class="screen-reader-text"><?php echo __('Click to view Halloween sale products', 'mrm'); ?></span>
                                            <span aria-hidden="true" class="mint-notification-counter__mint-button"> <?php echo __('FLAT', 'mrm'); ?> <strong class="mint-notification-counter__stroke-font">30%</strong> <?php echo __('OFF', 'mrm'); ?></span>
                                        </span>
                                            
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>

                    <button class="close-promotional-banner" type="button" aria-label="close banner">
                        <svg width="12" height="13" fill="none" viewBox="0 0 12 13" xmlns="http://www.w3.org/2000/svg">
                            <path stroke="#7A8B9A" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 1.97L1 11.96m0-9.99l10 9.99" />
                        </svg>
                    </button>


                </div>
            </div>
        </div>
        <script>
            var timeRemaining = <?php echo esc_js($time_remaining); ?>;

            function updateCountdown() {
                var endDate = new Date("2024-10-20 23:59:59").getTime();
                var now = new Date().getTime();
                var timeLeft = endDate - now;

                var days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                var hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));

                var daysElement = document.getElementById('mint-halloween-days');
                var hoursElement = document.getElementById('mint-halloween-hours');
                var minsElement = document.getElementById('mint-halloween-mins');

                if (daysElement) {
                    daysElement.innerHTML = days;
                }

                if (hoursElement) {
                    hoursElement.innerHTML = hours;
                }

                if (minsElement) {
                    minsElement.innerHTML = minutes;
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                updateCountdown();
                setInterval(updateCountdown, 60000); // Update every minute
            });

            // Update the countdown every second
            // setInterval(function() {
            //     var countdownElement    = document.getElementById('mailmint_countdown');
            //     var daysElement         = document.getElementById('mint-halloween-days');
            //     var hoursElement        = document.getElementById('mailmint_hours');
            //     var minutesElement      = document.getElementById('mailmint_minutes');

            //     // Decrease the remaining time
            //     timeRemaining--;

            //     // Calculate new days, hours, and minutes
            //     var days = Math.floor(timeRemaining / (60 * 60 * 24));
            //     var hours = Math.floor((timeRemaining % (60 * 60 * 24)) / (60 * 60));
            //     var minutes = Math.floor((timeRemaining % (60 * 60)) / 60);


            //     // Format values with leading zeros
            //     days = (days < 10) ? '0' + days : days;
            //     hours = (hours < 10) ? '0' + hours : hours;
            //     minutes = (minutes < 10) ? '0' + minutes : minutes;

            //     // Update the HTML
            //     daysElement.textContent = days;
            //     hoursElement.textContent = hours;
            //     minutesElement.textContent = minutes;

            //     // // Check if the countdown has ended
            //     // if (timeRemaining <= 0) {
            //     //     countdownElement.innerHTML = 'Campaign Ended';
            //     // }
            // }, 1000); // Update every second
        </script>
    <?php
    }

    /**
     * Adds internal CSS styles for the special occasion banners.
     */
    public function add_styles()
    {
    ?>
        <style id="mailmint-promotional-banner-style">
            @font-face {
                font-family: "Circular Std Bold";
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/circularstd-bold.woff2'; ?>) format("woff2"),
                    url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/circularstd-bold.woff'; ?>) format("woff");
                font-weight: normal;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: "Circular Std Book";
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/CircularStd-Book.woff2'; ?>) format("woff2"),
                    url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/CircularStd-Book.woff'; ?>) format("woff");
                font-weight: normal;
                font-style: normal;
                font-display: swap;
            }


            @font-face {
                font-family: "Inter";
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Inter-Bold.woff2'; ?>) format("woff2"),
                    url(<?php echo MRM_DIR_URL . 'assets/fonts/Inter-Bold.woff'; ?>) format("woff");
                font-weight: 700;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Lexend Deca';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/LexendDeca-SemiBold.woff2'; ?>) format("woff2"),
                    url(<?php echo MRM_DIR_URL . 'assets/fonts/LexendDeca-SemiBold.woff'; ?>) format("woff");
                font-weight: 600;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Lexend Deca';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/LexendDeca-Bold.woff2'; ?>) format("woff2"),
                    url(<?php echo MRM_DIR_URL . 'assets/fonts/LexendDeca-Bold.woff'; ?>) format("woff");
                font-weight: 700;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Lexend Deca';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/LexendDeca-ExtraBold.woff2'; ?>) format("woff2"),
                    url(<?php echo MRM_DIR_URL . 'assets/fonts/LexendDeca-ExtraBold.woff'; ?>) format("woff");
                font-weight: 800;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Syncopate';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Syncopate-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Abril Fatface';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/AbrilFatface-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Alegreya';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Alegreya-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Alegreya Sans';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/AlegreyaSans-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Anton';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Anton-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Arimo';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Arimo-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Arvo';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Arvo-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Catamaran';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Catamaran-Thin.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Della Respira';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/DellaRespira-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'DM Sans';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/DMSans-9ptRegular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Gilda Display';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/GildaDisplay-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Lato';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Lato-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Lora';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Lora-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Marcellus';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Marcellus-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Merriweather';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Merriweather-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Merriweather Sans';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/MerriweatherSans-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Montserrat';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Montserrat.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Nanum Gothic Coding';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/NanumGothicCoding.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Open Sans';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/OpenSans-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Neuton';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Neuton-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Noticia Text';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/NoticiaText-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Noto Sans';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/NotoSans-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Noto Sans Georgian';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/NotoSansGeorgian-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Playfair Display';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/PlayfairDisplay-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Recursive Sans';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/RecursiveSans-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Roboto';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Roboto-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Source Code Roman';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/SourceCodeRoman.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Source Sans';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/SourceSans.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Space Mono';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/SpaceMono-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Tiro Bangla';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/TiroBangla-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Work Sans';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/WorkSans-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Raleway';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Raleway.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Poppins';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Poppins-Regular.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Josefin Sans';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/JosefinSans-VariableFont_wght.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Quicksand';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/Quicksand-VariableFont_wght.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Jeanne Moderno';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/jeanne-moderno.woff2'; ?>) format("woff2");
            }

            @font-face {
                font-family: 'Jeanne Moderno';
                src: url(<?php echo MRM_DIR_URL . 'admin/assets/fonts/jeanne-moderno.woff'; ?>) format("woff");
            }


            .mailmint-tb__notification,
            .mailmint-tb__notification * {
                box-sizing: border-box;
            }

            .mailmint-tb__notification {
                width: calc(100% - 20px);
                margin: 20px 0 20px;
                background-image: url(<?php echo MRM_DIR_URL . 'admin/assets/images/banner-image/notification-br-bg.webp'; ?>);
                background-repeat: no-repeat;
                background-size: cover;
                position: relative;
                border: none;
                box-shadow: none;
                display: block;
                max-height: 110px;
            }

            .mailmint-tb__notification .banner-overflow {
                overflow: hidden;
                position: relative;
                width: 100%;
                z-index: 1;
            }

            .wp-anniversary-banner.notice {
                border: none;
                padding: 0;
                display: block !important;
                background: transparent;
                margin: 0;
            }

            .mailmint-tb__notification .close-promotional-banner {
                position: absolute;
                top: -10px;
                right: -9px;
                background: #fff;
                border: none;
                padding: 0;
                border-radius: 50%;
                cursor: pointer;
                z-index: 9;
                width: 30px;
                height: 30px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .mailmint-tb__notification .close-promotional-banner svg {
                width: 22px;
            }

            .mailmint-tb__notification .close-promotional-banner svg {
                display: block;
                width: 15px;
                height: 15px;
            }

            .mailmint-anniv__container {
                width: 100%;
                margin: 0 auto;
                max-width: 1640px;
                position: relative;
                padding-right: 15px;
                padding-left: 15px;
            }

            .mailmint-anniv__container-area {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .mailmint-anniv__content-area {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: space-evenly;
                max-width: 1310px;
                position: relative;
                padding-right: 15px;
                padding-left: 15px;
                margin: 0 auto;
                z-index: 1;
            }

            .mailmint-anniv__image--left {
                position: absolute;
                left: 140px;
                top: 50%;
                transform: translateY(-50%);
            }

            .mailmint-anniv__image--right {
                position: absolute;
                right: 0;
                top: 60%;
                transform: translateY(-50%);
            }

            .mailmint-anniv__image--group {
                display: flex;
                align-items: center;
                gap: 50px;
            }

            .mailmint-anniv__image--left img {
                width: 100%;
                max-width: 98px;
            }

            .mailmint-anniv__image--eid-mubarak img {
                width: 100%;
                max-width: 125px;
            }

            .mailmint-anniv__image--wpfunnel-logo img {
                width: 100%;
                max-width: 125px;
            }

            .mailmint-anniv__image--four img {
                width: 100%;
                max-width: 224px;
            }

            .mailmint-anniv__lead-text {
                display: flex;
                gap: 11px;
            }

            .mailmint-anniv__lead-text h2 {
                font-size: 42px;
                line-height: 1;
                margin: 0;
                color: #FFFFFF;
                font-weight: 700;
                font-family: 'Lexend Deca';

            }



            .mailmint-anniv__image--right img {
                width: 100%;
                max-width: 152px;
            }

            .mailmint-anniv__image figure {
                margin: 0;
            }

            .mailmint-anniv__text-container {
                position: relative;
                max-width: 330px;
            }

            .mailmint-anniv__campaign-text-images {
                position: absolute;
                top: -10px;
                right: -15px;
                max-width: 100%;
                max-height: 24px;
            }



            .mailmint-anniv__btn-area {
                display: flex;
                align-items: flex-end;
                justify-content: flex-end;
                position: relative;
            }

            .mailmint-anniv__btn-area svg {
                position: absolute;
                width: 70px;
                right: -35px;
            }

            .mailmint-anniv__btn {
                font-family: 'Lexend Deca';
                font-size: 20px;
                font-weight: 700;
                line-height: 1;
                text-align: center;
                border-radius: 13px;
                background: linear-gradient(0deg, #ACE7FF 0%, #FFFFFF 100%);
                ;
                box-shadow: 0px 11px 30px 0px rgba(19, 13, 57, 0.25);
                color: #2D29FF;
                padding: 17px 26px;
                display: inline-block;
                cursor: pointer;
                text-transform: capitalize;
                transition: all 0.5s linear;
                text-decoration: none;
            }

            a.mailmint-anniv__btn:hover {
                box-shadow: none;
            }

            .mailmint-anniv__btn-area a:focus {
                color: #fff;
                box-shadow: none;
                outline: 0px solid transparent;
            }

            .mailmint-anniv__btn:hover {
                background-color: #201cfe;
                color: #6E42D3;
            }

            .wpcartlift-banner-title p {
                margin: 0;
                font-weight: 700;
                max-width: 315px;
                font-size: 24px;
                color: #ffffff;
                line-height: 1.3;
            }

            @media only screen and (min-width: 1921px) {
                .mailmint-anniv__image--left img {
                    max-width: 108px;
                }
            }


            @media only screen and (max-width: 1710px) {

                .mailmint-anniv__image--left {
                    left: 100px;
                }

                .mailmint-anniv__lead-text h2 {
                    font-size: 36px;
                }

                .mailmint-anniv__content-area {
                    justify-content: center;
                }

                .mailmint-anniv__image--group {
                    gap: 30px;
                }

                .mailmint-anniv__content-area {
                    gap: 30px;
                }

                .mailmint-anniv__btn {
                    font-size: 18px;
                }

                .mailmint-anniv__btn-area svg {
                    position: absolute;
                    width: 70px;
                    right: -30px;
                }

            }


            @media only screen and (max-width: 1440px) {

                .mailmint-tb__notification {
                    max-height: 99px;
                }

                .mailmint-anniv__image--left {
                    left: 40px;
                }

                .mailmint-anniv__image--left img {
                    width: 90%;
                }

                .mailmint-anniv__image--eid-mubarak img {
                    width: 90%;
                }

                .mailmint-anniv__image--wpfunnel-logo img {
                    width: 90%;
                }

                .mailmint-anniv__image--four img {
                    width: 90%;
                }

                .mailmint-anniv__image--right img {
                    width: 90%;
                }

                .mailmint-anniv__lead-text h2 {
                    font-size: 28px;
                }

                .mailmint-anniv__image--group {
                    gap: 25px;
                }

                .mailmint-anniv__content-area {
                    gap: 30px;
                    justify-content: center;
                }

                .mailmint-anniv__btn {
                    font-size: 16px;
                    font-weight: 400;
                    border-radius: 30px;
                    padding: 12px 16px;
                }

                .mailmint-anniv__btn-area svg {
                    position: absolute;
                    width: 60px;
                    right: -15px;
                    top: -15px;
                }

            }


            @media only screen and (max-width: 1399px) {

                .mailmint-tb__notification {
                    max-height: 79px;
                }

                .mailmint-anniv__image--left {
                    left: 20px;
                }


                .mailmint-anniv__image--left img {
                    max-width: 78px;
                    opacity: .35;
                }

                .mailmint-anniv__image--eid-mubarak img {
                    max-width: 100px;
                }

                .mailmint-anniv__image--wpfunnel-logo img {
                    max-width: 108px;
                }

                .mailmint-anniv__image--four img {
                    max-width: 173px;
                }

                .mailmint-anniv__image--right img {
                    max-width: 121.5px;
                }

                .mailmint-anniv__lead-text h2 {
                    font-size: 24px;
                }

                .mailmint-anniv__image--group {
                    gap: 20px;
                }

                .mailmint-anniv__content-area {
                    gap: 35px;
                }

                .mailmint-anniv__btn {
                    font-size: 14px;
                    font-weight: 600;
                    border-radius: 30px;
                    padding: 12px 16px;
                }

                .mailmint-anniv__btn-area svg {
                    width: 45px;
                    right: -13px;
                    top: -21px;
                }

                .mailmint-anniv__image--right {
                    right: -9px;
                    top: 56%;
                }

            }

            @media only screen and (max-width: 1024px) {
                .mailmint-tb__notification {
                    max-height: 75px;
                }

                .mailmint-anniv__image--left img {
                    max-width: 76.39px;
                }

                .mailmint-anniv__image--eid-mubarak img {
                    max-width: 90px;
                }

                .mailmint-anniv__image--wpfunnel-logo img {
                    max-width: 100px;
                }

                .mailmint-anniv__image--four img {
                    max-width: 173px;
                }

                .mailmint-anniv__image--right img {
                    max-width: 111.5px;
                }

                .mailmint-anniv__lead-text h2 {
                    font-size: 22px;
                }

                .mailmint-anniv__lead-text svg {
                    width: 25px;
                    margin-top: -10px;
                }


                .mailmint-anniv__content-area {
                    gap: 30px;
                }

                .mailmint-anniv__image--group {
                    gap: 15px;
                }

                .mailmint-anniv__btn {
                    font-size: 12px;
                    line-height: 1.2;
                    padding: 11px 12px;
                    font-weight: 400;
                }

                .mailmint-anniv__btn {
                    box-shadow: none;
                }

                .mailmint-anniv__image--right,
                .mailmint-anniv__image--left {
                    display: none;
                }

                .mailmint-anniv__btn-area svg {
                    width: 40px;
                    right: -15px;
                    top: -23px;
                }


            }

            @media only screen and (max-width: 768px) {

                .mailmint-anniv__container-area {
                    padding: 0 15px;
                }

                .mailmint-anniv__container-area {
                    justify-content: center;
                    gap: 20px;
                }

                .mailmint-tb__notification {
                    max-height: 64px;
                }

                .mailmint-anniv__image--left img {
                    max-width: 76.39px;
                }

                .mailmint-anniv__image--eid-mubarak img {
                    max-width: 92px;
                }

                .mailmint-anniv__image--wpfunnel-logo img {
                    max-width: 90px;
                }

                .mailmint-anniv__image--four img {
                    max-width: 163px;
                }

                .mailmint-anniv__image--right img {
                    max-width: 111.5px;
                }

                .mailmint-anniv__lead-text h2 {
                    font-size: 22px;
                }

                .mailmint-anniv__content-area {
                    gap: 30px;
                }

                .mailmint-anniv__image--group {
                    gap: 15px;
                }

                .mailmint-tb__notification .close-promotional-banner {
                    width: 25px;
                    height: 25px;
                }

                .mailmint-anniv__image--group {
                    gap: 20px;
                }

                .mailmint-anniv__image--left,
                .mailmint-anniv__image--right {
                    display: none;
                }

                .mailmint-anniv__btn {
                    font-size: 12px;
                    line-height: 1;
                    font-weight: 400;
                    padding: 10px 12px;
                    margin-left: 0;
                    box-shadow: none;
                }

                .mailmint-anniv__content-area {
                    display: contents;
                    gap: 25px;
                    text-align: center;
                    align-items: center;
                }

                .mailmint-anniv__lead-text svg {
                    width: 22px;
                    margin-top: -8px;
                }


            }

            @media only screen and (max-width: 767px) {

                .mailmint-anniv__image--right,
                .mailmint-anniv__image--left {
                    display: none;
                }

                .mailmint-anniv__stroke-font {
                    font-size: 16px;
                }

                .mailmint-anniv__content-area {
                    display: contents;
                    gap: 25px;
                    text-align: center;
                    align-items: center;
                }

                .mailmint-anniv__btn-area {
                    justify-content: center;
                    padding-top: 5px;
                }

                .mailmint-anniv__btn {
                    font-size: 12px;
                    padding: 15px 24px;
                }

                .mailmint-anniv__image--group {
                    gap: 10px;
                    padding: 0;
                }
            }


            /* Halloween */

            .mint-notification-counter {
                position: relative;
                background-image: url(<?php echo esc_url(MRM_DIR_URL . 'admin/assets/images/halloween/promotional-banner.png'); ?>);
                background-position: center;
                background-repeat: no-repeat;
                background-size: 100% 100%;
                object-fit: cover;
                background-color: #03031E;
                z-index: 1111;
                padding: 9px 0 4px;
            }

            .mint-notification-counter__container {
                position: relative;
                width: 100%;
                max-height: 110px;
                max-width: 1310px;
                margin: 0 auto;
                padding: 0px 15px;
            }

            .mint-notification-counter__content {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .mint-notification-counter__figure-logo {
                max-width: 268px;
            }

            .mint-notification-counter__figure-percentage {
                max-width: 248px;
                margin-left: -75px;
            }

            .mint-notification-counter__img {
                width: 100%;
                max-width: 100%;
            }

            .mint-notification-counter__list {
                display: flex;
                justify-content: center;
                gap: 10px;
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .mint-notification-counter__item {
                display: flex;
                flex-direction: column;
                width: 56.14px;
                font-family: "Circular Std Book";
                font-size: 15px;
                font-style: normal;
                font-weight: 500;
                line-height: normal;
                letter-spacing: 0.75px;
                text-transform: uppercase;
                text-align: center;
                color: #FFF;
            }

            .mint-notification-counter__time {
                font-size: 32px;
                font-family: "Inter";
                font-style: normal;
                font-weight: 700;
                line-height: normal;
                color: #fff;
                text-align: center;
                margin-bottom: 6px;
                border-radius: 3px 3px 10px 10px;
                border-top: 1px solid #5440f4;
                border-right: 1px solid #5440f4;
                border-bottom: 5px solid #5440f4;
                border-left: 1px solid #5440f4;
                background: linear-gradient(155deg, #201CFE 2.02%, #5440f4 55.1%, #100E35 131.47%);
            }

            .mint-notification-counter__btn-area {
                display: flex;
                align-items: flex-end;
                justify-content: flex-end;
                margin-bottom: 30px;
            }

            .mint-notification-counter__btn {
                position: relative;
                font-family: "Inter";
                font-size: 20px;
                line-height: normal;
                color: #FFF;
                text-align: center;
                filter: drop-shadow(0px 30px 60px rgba(21, 19, 119, 0.20));
                padding: 12px 22px;
                display: inline-block;
                cursor: pointer;
                text-transform: uppercase;
                background: #573BFF;
                text-decoration: none;
                border-radius: 10px;
                font-weight: 400;
                transition: all 0.3s ease;
            }

            .mint-notification-counter__btn:hover {
                background-color: #201cfe;
                color: #ffffff;
            }

            .mint-notification-counter__stroke-font {
                font-size: 26px;
                font-family: "Inter";
                font-weight: 700;
            }

            /* Media Queries */
            @media only screen and (max-width: 1199px) {
                .mint-notification-counter__container {
                    max-width: 1010px;
                }
                .mint-notification-counter__figure-percentage {
                    margin-left: -60px;
                }
                .mint-notification-counter__figure-percentage,
                .mint-notification-counter__figure-logo {
                    max-width: 220px;
                }
                .mint-notification-counter__btn {
                    font-size: 15px;
                    line-height: 20px;
                    padding: 10px 16px;
                    font-weight: 400;
                }
                .mint-notification-counter__stroke-font {
                    font-size: 20px;
                }

                .mint-notification-counter {
                    padding: 5px 0 4px;
                }
                .mint-notification-counter__figure-percentage {
                    margin-left: 0px;
                }
                .mint-notification-counter__figure-logo {
                    max-width: 160px;
                }
                .mint-notification-counter__figure-percentage {
                    max-width: 150px;
                }
                .mint-notification-counter__btn {
                    font-size: 14px;
                    line-height: 18px;
                    padding: 9px 10px;
                }
                .mint-notification-counter__stroke-font {
                    font-size: 18px;
                }
                .mint-notification-counter__time {
                    font-size: 24px;
                }
            }

            @media only screen and (max-width: 767px) {
                .mint-notification-counter {
                    padding: 50px 0;
                    background-image: url(<?php echo esc_url(MRM_DIR_URL . 'admin/assets/images/halloween/promotional-banner-mobile.webp'); ?>);
                }
                .mint-notification-counter__container {
                    max-height: none;
                }
                .mint-notification-counter__figure-logo {
                    max-width: 174px;
                }
                .mint-notification-counter__figure-percentage {
                    max-width: 150px;
                }
                .mint-notification-counter__content {
                    flex-flow: column;
                    gap: 12px;
                    text-align: center;
                }
                .mint-notification-counter__btn {
                    font-size: 16px;
                    padding: 11px 16px;
                }
                .mint-notification-counter__stroke-font {
                    font-size: 22px;
                }
            }
        </style>

<?php
    }
}
