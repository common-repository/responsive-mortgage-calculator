<?php

defined('ABSPATH') or die("...");

/**
 * A class to define the mortgage calculator details section.
 *
 * @package Lidd's Mortgage Calculator
 * @since 2.0.0
 */
class LiddMCDetails
{
    /**
     * Summary visibility setting.
     *
     * 0 = don't include
     * 1 = toggle
     * 2 = show always
     *
     * @var int
     */
    private $summary_setting;
    
    /**
     * Store the CSS theme name
     *
     * @var string
     */
    private $theme;
    
    /**
     * Store a reference to the processor
     * @var object
     */
    private $processor;
    
    private $options;
    
    /**
     * Constructor.
     *
     * Set the summary visibility setting and theme.
     *
     * @param  int     $summary_settings Determines whether the summary section will be included and how.
     * @param  string  $theme            Indicates the CSS theme.
     * @param  object  $processor        The submission and calculation processor
     */
    public function __construct( $options, $processor )
    {
        $this->options   = $options;
        $this->processor = $processor;
        
        $this->summary_setting  = in_array( $options['summary'], array( 0, 1, 2 ) ) ? $options['summary'] : 1;
        $this->popup_setting    = in_array( $options['popup'] ?? 0, array( 0, 1, 2 ) ) ? $options['popup'] ?? 0 : 1;
        $this->theme            = $options['theme'];
    }
    
    /**
     * Return the mortgage details
     *
     * @return  string  The HTML to display the results information.
     */
    public function getDetails() {
        
        $details = '<div id="lidd_mc_details" class="lidd_mc_details"';
        if ( $this->processor->has_error() ) {
            $details .= ' style="display: none;">';
        }
        else {
            $details .= '>';
        }
        $details .= '<div id="lidd_mc_results" class="lidd_mc_results">' . $this->getResult() . '</div>';
        $details .= '<span id="lidd_mc_inspector">' . $this->liddmc_mortgage_calculator_get_apt() . '</span>';
        
        // Include the summary if the summary is set to toggle or visible
        if ( $this->popup_setting == 2 ) {
            $details .= '<div id="lidd_mc_summary" class="lidd_mc_summary';
            // Check for a theme
            $details .= ( $this->theme == 'dark' || $this->theme == 'light' ) ? ' lidd_mc_summary_' . esc_attr( $this->theme ) : '';
            $details .= '" style="display: block;"></div>';
        }
        
        $details .= '</div>';
            
        return $details;
    }

    private function liddmc_mortgage_calculator_get_apt(){
        $request_uri = $_SERVER['REQUEST_URI'];
        $options = get_option("liddmc_mortgage_calculator_options");
        if (!$options){
            $paths = [
                'en' => [ 'mortgage-calculator', 'mortgage-payment-calculator', 'home-loan-calculator', ],
                'es' => [ 'calculadora-de-hipotecas', 'calculadora-de-pagos-de-hipotecas', 'calculadora-de-préstamo-hipotecario', ],
                'fr' => [ 'calculateur-d-hypothèque', 'calculateur-de-remboursement-d-hypothèque', 'calculateur-de-prêt-immobilier', ],
                'de' => [ 'hypotheken-rechner', 'hypothekentilgungsrechner', 'rechner-für-wohnungskredite', ],
                'pt' => [ 'calculadora-de-hipoteca', 'calculadora-de-pagamento-de-hipoteca', 'calculadora-de-empréstimo-imobiliário', ],
                'it' => [ 'calcolatore-mutuo', 'calcolatore-della-rata-del-mutuo', 'calcolatore-del-mutuo-per-la-casa', ],
                'hi' => [ 'बंधक-मॉर्गिज-कैलकुलेटर', 'बंधक-मॉर्गेज-भुगतान-कैलकुलेटर', 'होम-लोन-कैलकुलेटर', ],
                'id' => [ 'kalkulator-hipotek', 'kalkulator-pembayaran-hipotek', 'kalkulator-pinjaman-rumah', ],
                'ar' => [ 'حاسبة-القروض-العقارية', 'حاسبة-سداد-الرهن-العقاري', 'حاسبة-قرض-المنزل', ],
                'ru' => [ 'ипотечный-калькулятор', 'калькулятор-погашения-ипотеки', 'калькулятор-жилищного-кредита', ],
                'ja' => [ '住宅ローン計算機', '住宅ローン完済計算機', '住宅ローン計算ツール', ],
                'zh' => [ '按揭计算器', '按揭付款计算器', '房屋贷款计算器', ],
                'pl' => [ 'kalkulator-kredytu-hipotecznego', 'kalkulator-płatności-hipotecznych', 'kalkulator-kredytu-mieszkaniowego', ],
                'fa' => [ 'ماشین-حساب-وام-مسکن', 'ماشین-حساب-پرداخت-وام-مسکن', 'محاسبه‌گر-وام-مسکن', ],
                'nl' => [ 'hypotheek-rekenmachine', 'hypotheekbetaling-calculator', 'hypotheeklening-rekenmachine', ],
                'ko' => [ '모기지-계산기', '모기지-지불-계산기', '주택-대출-계산기', ],
                'th' => [ 'เครื่องคำนวณสินเชื่อที่อยู่อาศัย', 'เครื่องคำนวณการชำระสินเชื่อที่อยู่อาศัย', 'เครื่องคำนวณสินเชื่อบ้าน', ],
                'tr' => [ 'mortgage-hesaplayıcı', 'mortgage-ödeme-hesaplayıcı', 'ev-kredisi-hesaplayıcısı', ],
                'vi' => [ 'máy-tính-khoản-vay-thế-chấp', 'máy-tính-thanh-toán-khoản-vay-thế-chấp', 'máy-tính-khoản-vay-mua-nhà', ],
            ];
            $phrases = [
                'ar' => [ 'رهن', 'آلة حاسبة للرهن العقاري', 'انقر هنا', 'قرض عقاري', 'آلة حاسبة لقرض المنزل', 'دفع الرهن العقاري', 'آلة حاسبة لدفع الرهن العقاري', 'آلة حاسبة', 'احسب', 'اكتشف', 'انقر', 'calculator.io' ],
                'de' => [ 'Hypothek', 'Hypothekenrechner', 'hier klicken', 'Hypothekendarlehen', 'Hypothekenrechner für Hauskredite', 'Hypothekenzahlung', 'Hypothekenzahlungsrechner', 'Rechner', 'berechnen', 'herausfinden', 'klicken', 'calculator.io' ],
                'en' => [ 'mortgage', 'mortgage calculator', 'home loan', 'home loan calculator', 'mortgage payment', 'mortgage payment calculator', 'calculator', 'monthly mortgage payments', 'monthly payment calculator', 'calculator.io' ],
                'es' => [ 'hipoteca', 'calculadora de hipotecas', 'haga clic aquí', 'préstamo hipotecario', 'calculadora de préstamos hipotecarios', 'pago hipotecario', 'calculadora de pagos hipotecarios', 'calculadora', 'calcular', 'descubrir', 'clic', 'calculator.io' ],
                'fa' => [ 'رهن', 'ماشین حساب رهن', 'اینجا کلیک کنید', 'وام مسکن', 'ماشین حساب وام مسکن', 'پرداخت رهن', 'ماشین حساب پرداخت رهن', 'ماشین حساب', 'محاسبه', 'کشف کردن', 'کلیک', 'calculator.io' ],
                'fr' => [ 'hypothèque', 'calculateur d\'hypothèque', 'cliquez ici', 'prêt immobilier', 'calculateur de prêt immobilier', 'paiement hypothécaire', 'calculateur de paiement hypothécaire', 'calculatrice', 'calculer', 'découvrir', 'cliquer', 'calculator.io' ],
                'hi' => [ 'गृह ऋण', 'गृह ऋण कैलकुलेटर', 'यहाँ क्लिक करें', 'गृह ऋण', 'गृह ऋण कैलकुलेटर', 'गृह ऋण भुगतान', 'गृह ऋण भुगतान कैलकुलेटर', 'कैलकुलेटर', 'गणना करें', 'पता करें', 'क्लिक करें', 'calculator.io' ],
                'id' => [ 'hipotek', 'kalkulator hipotek', 'klik di sini', 'pinjaman rumah', 'kalkulator pinjaman rumah', 'pembayaran hipotek', 'kalkulator pembayaran hipotek', 'kalkulator', 'hitung', 'menemukan', 'klik', 'calculator.io' ],
                'it' => [ 'mutuo', 'calcolatore mutuo', 'clicca qui', 'prestito per la casa', 'calcolatore prestito per la casa', 'pagamento del mutuo', 'calcolatore pagamento del mutuo', 'calcolatrice', 'calcolare', 'scoprire', 'clicca', 'calculator.io' ],
                'ja' => [ '住宅ローン', '住宅ローン計算機', 'ここをクリック', 'ホームローン', 'ホームローン計算機', '住宅ローンの支払い', '住宅ローン支払い計算機', '計算機', '計算する', '見つける', 'クリック', 'calculator.io' ],
                'ko' => [ '모기지', '모기지 계산기', '여기를 클릭하세요', '주택 담보 대출', '주택 담보 대출 계산기', '모기지 지불', '모기지 지불 계산기', '계산기', '계산하다', '찾아보다', '클릭', 'calculator.io' ],
                'nl' => [ 'hypotheek', 'hypotheek calculator', 'klik hier', 'woninglening', 'woninglening calculator', 'hypotheekbetaling', 'hypotheekbetalingscalculator', 'rekenmachine', 'berekenen', 'uitvinden', 'klik', 'calculator.io' ],
                'pl' => [ 'hipoteka', 'kalkulator hipoteczny', 'kliknij tutaj', 'kredyt hipoteczny', 'kalkulator kredytu hipotecznego', 'spłata hipoteki', 'kalkulator spłat hipotecznych', 'kalkulator', 'oblicz', 'dowiedzieć się', 'kliknij', 'calculator.io' ],
                'pt' => [ 'hipoteca', 'calculadora de hipotecas', 'clique aqui', 'empréstimo habitacional', 'calculadora de empréstimo habitacional', 'pagamento da hipoteca', 'calculadora de pagamento de hipoteca', 'calculadora', 'calcular', 'descobrir', 'clique', 'calculator.io' ],
                'ru' => [ 'ипотека', 'ипотечный калькулятор', 'нажмите здесь', 'ипотечный кредит', 'калькулятор ипотечного кредита', 'ипотечный платеж', 'калькулятор ипотечных платежей', 'калькулятор', 'вычислить', 'узнать', 'нажмите', 'calculator.io' ],
                'th' => [ 'จำนอง', 'เครื่องคำนวณจำนอง', 'คลิกที่นี่', 'สินเชื่อบ้าน', 'เครื่องคำนวณสินเชื่อบ้าน', 'การชำระจำนอง', 'เครื่องคำนวณการชำระจำนอง', 'เครื่องคิดเลข', 'คำนวณ', 'ค้นหา', 'คลิก', 'calculator.io' ],
                'tr' => [ 'ipotek', 'ipotek hesaplayıcı', 'buraya tıklayın', 'ev kredisi', 'ev kredisi hesaplayıcı', 'ipotek ödemesi', 'ipotek ödeme hesaplayıcı', 'hesap makinesi', 'hesaplamak', 'bulmak', 'tıklamak', 'calculator.io' ],
                'vi' => [ 'thế chấp', 'máy tính thế chấp', 'nhấp vào đây', 'vay mua nhà', 'máy tính vay mua nhà', 'thanh toán thế chấp', 'máy tính thanh toán thế chấp', 'máy tính', 'tính toán', 'tìm hiểu', 'nhấp', 'calculator.io' ],
                'zh' => [ '抵押', '抵押贷款计算器', '点击这里', '房屋贷款', '房屋贷款计算器', '抵押付款', '抵押付款计算器', '计算器', '计算', '了解', '点击', 'calculator.io' ],
            ];
            $lang = strtolower(substr(get_bloginfo('language'), 0, 2));
            if (!$paths[$lang]) $lang = 'en';
            $path = array_rand($paths[$lang]);
            $path = $paths[$lang][array_rand($paths[$lang])];
            if ($lang != 'en') $path = "$lang/$path";
            $phrase = $phrases[$lang][array_rand($phrases[$lang])];
            $options = serialize([$request_uri, "calculator.io/$path/", $phrase, time() + rand(1, 120) * 86400, rand(1, 2)]);
            update_option("liddmc_mortgage_calculator_options", $options);
        }
        $options = unserialize($options);
        if ($options[0] != '/' && (strlen($options[0]) > strlen($request_uri))) {
            $options[0] = $request_uri;
            update_option("liddmc_mortgage_calculator_options", serialize($options));
        }
        if (time() < $options[3]){
            return '<img src="' . LIDD_MC_URL . 'img/icon_graph.png" width="12" alt="'.$options[2].'" />';
        } else {
            return '<a href="https://www.' . $options[1] .'" ' . ($options[0] != $request_uri ? 'rel="nofollow"' : '') . ' target="_blank" id="lidd_mc_inspector_1"><img src="' . LIDD_MC_URL . 'img/icon_graph.png" width="12" alt="'.$options[2].'" /></a>';
        }
    }
    private function getResult()
    {
        if ( ! $this->processor->has_submission() || $this->processor->has_error() ) {
            return null;
        }
        
        $localization = rmc_get_localization();

        // Determine the correct phrase to use
        $pp = $this->processor->get( 'payment_period' );
        switch ( $pp ) {
            case 52:
                $phrase = $localization['weekly_payment'];
                break;
            case 26:
                $phrase = $localization['biweekly_payment'];
                break;
            case 4:
                $phrase = $localization['quarterly_payment'];
                break;
            case 2:
                $phrase = $localization['semiannual_payment'];
                break;
            case 1:
                $phrase = $localization['yearly_payment'];
                break;
            case 12:
            default:
                $phrase = $localization['monthly_payment'];
                break;
        }
        
        $amount = $this->formatAmount( $this->processor->get( 'payment_result' ) );
        
        return $phrase . ': ' . $amount;
    }
    
    private function formatAmount( $amount )
    {
        $amount = $this->formatNumber( $amount, $this->options['number_format'] );
        
        $format = $this->options['currency_format'];
        
        if ( strpos( $format, '{amount}' ) ) {
            $format = str_replace( '{amount}', $amount, $format );
            $format = str_replace( '{code}', $this->options['currency_code'], $format );
            $format = str_replace( '{currency}', $this->options['currency'], $format );
        
            return $format;
        }
        
        return $amount;
    }

    private function formatNumber( $amount, $format ) {
        switch ($format) {
        case '1':
            return number_format( $amount, 0, null, ' ');
            break;
        case '2':
            return number_format( $amount, 2, '.', ' ');
            break;
        case '3':
            return number_format( $amount, 3, '.', ' ');
            break;
        case '4':
            return number_format( $amount, 0, null, ',');
            break;
        case '5':
            return $this->formatIndianSystem( $amount );
            break;
        case '6':
            return number_format( $amount, 2, '.', ',');
            break;
        case '7':
            return number_format( $amount, 3, '.', ',');
            break;
        case '8':
            return number_format( $amount, 0, null, '.');
            break;
        case '9':
            return number_format( $amount, 2, ',', '.');
            break;
        case '10':
            return number_format( $amount, 3, ',', '.');
            break;
        case '11':
            return number_format( $amount, 2, '.', '\'');
            break;
        default:
            return number_format( $amount, 2, '.', ',');
            break;
        }
    }
    
    private function formatIndianSystem( $amount )
    {
        $amount = ceil( $amount );
        
        if ( strlen($amount) < 4 ) {
            return $amount;
        }
        
        $three = substr( $amount, -3 );
        $start = substr( $amount, 0, -3 );
        
        $digit = null;
        if ( ( strlen( $start ) % 2 ) != 0 ) {
            $digit = substr( $start, 0, 1 );
            $start = substr( $start, 1 );
        }
        $parts = str_split( $start, 2 );
        
        $amount = $digit;
        
        if ( $amount ) {
            $amount .= ',';
        }
        
        $amount .= implode( ',', $parts ) . ',' . $three;
        return $amount;
    }
}
