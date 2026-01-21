<?php
/*
Plugin Name: Ramadan Calendar Bangladesh
Description: Ramadan Calendar Bangladesh is a comprehensive WordPress plugin that provides accurate Sehri and Iftar times for all major divisions in Bangladesh. The plugin displays times in Bangla (Bengali) language and automatically adjusts times based on geographical locations within Bangladesh.
Author: Monirud Jamman Ashik
Author URI: http://mjashik.com/
License: GPL3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Version: 1.1
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function mjashik_ramadan_add_admin_menu() {
    add_menu_page(
        'Ramadan Time Settings',
        'Ramadan Time',
        'manage_options',
        'mjashik-ramadan-settings',
        'mjashik_ramadan_settings_page',
        'dashicons-calendar-alt',
        5
    );
}
add_action('admin_menu', 'mjashik_ramadan_add_admin_menu');

function mjashik_ramadan_admin_enqueue_scripts($hook) {
    if ('toplevel_page_mjashik-ramadan-settings' !== $hook) {
        return;
    }
    wp_enqueue_style('ramadan-calendar-admin-style', plugins_url('css/style.css', __FILE__), array(), '1.1');
}
add_action('admin_enqueue_scripts', 'mjashik_ramadan_admin_enqueue_scripts');

function mjashik_ramadan_settings_page() {
    ?>
<div class="wrap">
    <h1><?php echo esc_html__('Ramadan Time Settings', 'ramadan-calendar-bangladesh'); ?></h1>

    <div class="mjashik-ramadan-admin-card">
        <h2><?php echo esc_html__('Available Shortcodes', 'ramadan-calendar-bangladesh'); ?></h2>

        <div class="mjashik-ramadan-shortcode-item">
            <h3><?php echo esc_html__('Daily Timetable', 'ramadan-calendar-bangladesh'); ?></h3>
            <code class="mjashik-ramadan-shortcode-code">[mjashik_ramadan_time]</code>
            <p><?php echo esc_html__('Shows today\'s Sehri and Iftar times for all divisions in Bangladesh.', 'ramadan-calendar-bangladesh'); ?></p>
        </div>

        <div class="mjashik-ramadan-shortcode-item">
            <h3><?php echo esc_html__('Full Calendar', 'ramadan-calendar-bangladesh'); ?></h3>
            <code class="mjashik-ramadan-shortcode-code">[mjashik_ramadan_calendar]</code>
            <p><?php echo esc_html__('Displays the complete Ramadan calendar for 2026.', 'ramadan-calendar-bangladesh'); ?></p>
        </div>
    </div>
</div>
<?php
}

function mjashik_ramadan_add_settings_link($links) {
    $settings_link = '<a href="admin.php?page=mjashik-ramadan-settings">' . __('Settings', 'ramadan-calendar-bangladesh') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'mjashik_ramadan_add_settings_link');

function mjashik_get_wp_timezone() {
    $timezone_string = get_option('timezone_string');
    if ($timezone_string) {
        return new DateTimeZone($timezone_string);
    }
    
    $offset = get_option('gmt_offset');
    $hours = (int) $offset;
    $minutes = abs(($offset - (int) $offset) * 60);
    $offset_string = sprintf('%+03d:%02d', $hours, $minutes);
    return new DateTimeZone($offset_string);
}

function mjashik_get_ramadan_data() {
    return '{
        "19-02-2026": {"sehri": "5:12", "iftar": "5:57"},
        "20-02-2026": {"sehri": "5:11", "iftar": "5:58"},
        "21-02-2026": {"sehri": "5:10", "iftar": "5:58"},
        "22-02-2026": {"sehri": "5:10", "iftar": "5:59"},
        "23-02-2026": {"sehri": "5:09", "iftar": "5:59"},
        "24-02-2026": {"sehri": "5:08", "iftar": "6:00"},
        "25-02-2026": {"sehri": "5:07", "iftar": "6:00"},
        "26-02-2026": {"sehri": "5:06", "iftar": "6:01"},
        "27-02-2026": {"sehri": "5:05", "iftar": "6:01"},
        "28-02-2026": {"sehri": "5:04", "iftar": "6:02"},
        "01-03-2026": {"sehri": "5:03", "iftar": "6:02"},
        "02-03-2026": {"sehri": "5:02", "iftar": "6:03"},
        "03-03-2026": {"sehri": "5:01", "iftar": "6:03"},
        "04-03-2026": {"sehri": "5:00", "iftar": "6:04"},
        "05-03-2026": {"sehri": "4:59", "iftar": "6:04"},
        "06-03-2026": {"sehri": "4:58", "iftar": "6:05"},
        "07-03-2026": {"sehri": "4:57", "iftar": "6:05"},
        "08-03-2026": {"sehri": "4:56", "iftar": "6:06"},
        "09-03-2026": {"sehri": "4:55", "iftar": "6:06"},
        "10-03-2026": {"sehri": "4:54", "iftar": "6:06"},
        "11-03-2026": {"sehri": "4:53", "iftar": "6:07"},
        "12-03-2026": {"sehri": "4:52", "iftar": "6:07"},
        "13-03-2026": {"sehri": "4:51", "iftar": "6:08"},
        "14-03-2026": {"sehri": "4:50", "iftar": "6:08"},
        "15-03-2026": {"sehri": "4:49", "iftar": "6:09"},
        "16-03-2026": {"sehri": "4:48", "iftar": "6:09"},
        "17-03-2026": {"sehri": "4:47", "iftar": "6:10"},
        "18-03-2026": {"sehri": "4:46", "iftar": "6:10"},
        "19-03-2026": {"sehri": "4:45", "iftar": "6:11"},
        "20-03-2026": {"sehri": "4:44", "iftar": "6:11"}
    }';
}

function mjashik_convert_to_bangla($input) {
    $translations = [
        'numbers' => [
            "0" => "০", "1" => "১", "2" => "২", "3" => "৩", "4" => "৪",
            "5" => "৫", "6" => "৬", "7" => "৭", "8" => "৮", "9" => "৯"
        ],
        'months' => [
            "January" => "জানুয়ারী", "February" => "ফেব্রুয়ারী", "March" => "মার্চ",
            "April" => "এপ্রিল", "May" => "মে", "June" => "জুন",
            "July" => "জুলাই", "August" => "আগস্ট", "September" => "সেপ্টেম্বর",
            "October" => "অক্টোবর", "November" => "নভেম্বর", "December" => "ডিসেম্বর"
        ],
        'days' => [
            "Saturday" => "শনিবার", "Sunday" => "রবিবার", "Monday" => "সোমবার",
            "Tuesday" => "মঙ্গলবার", "Wednesday" => "বুধবার", 
            "Thursday" => "বৃহস্পতিবার", "Friday" => "শুক্রবার"
        ]
    ];

    $output = $input;
    foreach ($translations as $category) {
        $output = str_replace(array_keys($category), array_values($category), $output);
    }
    return $output;
}

function mjashik_get_ramadan_time_dhaka($date) {
    $schedule = json_decode(mjashik_get_ramadan_data(), true);
    return isset($schedule[$date]) ? $schedule[$date] : null;
}

function mjashik_get_city_adjustments($city, $time_type) {
    $adjustments = [
        'ঢাকা' => ['sehri' => 0, 'iftar' => 0],
        'চট্টগ্রাম' => ['sehri' => -2, 'iftar' => -8],
        'সিলেট' => ['sehri' => -9, 'iftar' => -4],
        'রাজশাহী' => ['sehri' => 5, 'iftar' => 8],
        'বরিশাল' => ['sehri' => 2, 'iftar' => -2],
        'খুলনা' => ['sehri' => 6, 'iftar' => 2],
        'রংপুর' => ['sehri' => -1, 'iftar' => 6],
        'ময়মনসিংহ' => ['sehri' => -3, 'iftar' => 1]
    ];
    
    return isset($adjustments[$city][$time_type]) ? $adjustments[$city][$time_type] : 0;
}

function mjashik_get_adjusted_time($city, $time_type) {
    $wp_timezone = mjashik_get_wp_timezone();
    $current_date = current_time('Y-m-d');
    $formatted_date = gmdate('d-m-Y', strtotime($current_date));
    
    $dhaka_times = mjashik_get_ramadan_time_dhaka($formatted_date);
    if (!$dhaka_times || !isset($dhaka_times[$time_type])) {
        return "---";
    }

    $time = $dhaka_times[$time_type];
    $datetime = new DateTime($current_date . ' ' . $time, $wp_timezone);
    
    $adjustment = mjashik_get_city_adjustments($city, $time_type);
    if ($adjustment != 0) {
        $datetime->modify("$adjustment minutes");
    }
    
    return mjashik_convert_to_bangla($datetime->format('H:i'));
}

function mjashik_is_ramadan_over() {
    $calendar_data = json_decode(mjashik_get_ramadan_data(), true);
    if (empty($calendar_data)) {
        return true;
    }
    
    $wp_timezone = mjashik_get_wp_timezone();
    $current_date = new DateTime('now', $wp_timezone);
    $formatted_current_date = $current_date->format('d-m-Y');
    
    $last_date = array_key_last($calendar_data);
    return $formatted_current_date > $last_date;
}

function mjashik_ramadan_time_function() {
    if (mjashik_is_ramadan_over()) {
        return '<div class="eid-mubarak-container">
            <div class="eid-mubarak-title">' . esc_html__('ঈদ মোবারক', 'ramadan-calendar-bangladesh') . '</div>
            <div class="eid-mubarak-content">' . 
                esc_html__('আপনার ও আপনার পরিবারের সকল সদস্যদের ঈদ মোবারক।', 'ramadan-calendar-bangladesh') . '<br>' . 
                esc_html__('আল্লাহ আমাদের সকলের রোজা, নামাজ ও ইবাদত কবুল করুন।', 'ramadan-calendar-bangladesh') . '
            </div>
        </div>';
    }

    $wp_timezone = mjashik_get_wp_timezone();
    $datetime = new DateTime('now', $wp_timezone);
    
    $today_date = $datetime->format('d F Y');
    $today_day = $datetime->format('l');
    $today_date_bangla = mjashik_convert_to_bangla($today_date);
    $today_day_bangla = mjashik_convert_to_bangla($today_day);

    $html = '
    <table class="mjashik-ramadan-table">
        <thead>
            <tr>
                <th colspan="3">
                    <div style="font-size: 20px; margin-bottom: 2px;">' . esc_html__('সাহরি ও ইফতারের সময়সূচি', 'ramadan-calendar-bangladesh') . '</div>
                    <div><strong>' . esc_html($today_date_bangla) . ' (' . esc_html($today_day_bangla) . ')</strong></div>
                </th>
            </tr>
            <tr style="font-size: 17px;">
                <th>' . esc_html__('বিভাগ', 'ramadan-calendar-bangladesh') . '</th>
                <th>' . esc_html__('সাহরি শেষ', 'ramadan-calendar-bangladesh') . '</th>
                <th>' . esc_html__('ইফতার', 'ramadan-calendar-bangladesh') . '</th>
            </tr>
        </thead>
        <tbody>';

    $cities = ['ঢাকা', 'চট্টগ্রাম', 'সিলেট', 'রাজশাহী', 'বরিশাল', 'খুলনা', 'রংপুর', 'ময়মনসিংহ'];
    foreach ($cities as $city) {
        $html .= '<tr>
            <td>' . esc_html($city) . '</td><td>' . esc_html(mjashik_get_adjusted_time($city, 'sehri')) . ' ' . esc_html__('মিঃ', 'ramadan-calendar-bangladesh') . '</td>
            <td>' . esc_html(mjashik_get_adjusted_time($city, 'iftar')) . ' ' . esc_html__('মিঃ', 'ramadan-calendar-bangladesh') . '</td>
        </tr>';
    }

    $html .= '</tbody></table>';
    return $html;
}

function mjashik_ramadan_calendar_shortcode() {
    $calendar_data = json_decode(mjashik_get_ramadan_data(), true);
    if (empty($calendar_data)) {
        return '<p>' . esc_html__('কোন তথ্য পাওয়া যায়নি।', 'ramadan-calendar-bangladesh') . '</p>';
    }

    $wp_timezone = mjashik_get_wp_timezone();
    
    $output = '';
    
    $output .= '<table class="ramadan-calendar">
        <thead>
            <tr>
                <th>' . esc_html__('রমজান', 'ramadan-calendar-bangladesh') . '</th>
                <th>' . esc_html__('তারিখ', 'ramadan-calendar-bangladesh') . '</th>
                <th>' . esc_html__('দিন', 'ramadan-calendar-bangladesh') . '</th>
                <th>' . esc_html__('সাহরি শেষ', 'ramadan-calendar-bangladesh') . '<br>' . esc_html__('সময় (সকাল)', 'ramadan-calendar-bangladesh') . '</th>
                <th>' . esc_html__('ইফতার', 'ramadan-calendar-bangladesh') . '<br>' . esc_html__('সময় (সন্ধ্যা)', 'ramadan-calendar-bangladesh') . '</th>
            </tr>
        </thead>
        <tbody>';
    
    $ramadan_day = 1;
    $today = current_time('d-m-Y');

    foreach ($calendar_data as $date => $times) {
        $date_obj = new DateTime($date);
        $formatted_date = mjashik_convert_to_bangla($date_obj->format('d F Y'));
        $day_name = mjashik_convert_to_bangla($date_obj->format('l'));
        $sehri_time = mjashik_convert_to_bangla($times['sehri']);
        $iftar_time = mjashik_convert_to_bangla($times['iftar']);
        
        $is_today = ($date === $today) ? ' class="today"' : '';
        
        $output .= sprintf(
            '<tr%s>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s %s</td>
                <td>%s %s</td>
            </tr>',
            $is_today,
            esc_html(mjashik_convert_to_bangla((string)$ramadan_day)),
            esc_html($formatted_date),
            esc_html($day_name),
            esc_html($sehri_time),
            esc_html__('মিঃ', 'ramadan-calendar-bangladesh'),
            esc_html($iftar_time),
            esc_html__('মিঃ', 'ramadan-calendar-bangladesh')
        );
        
        $ramadan_day++;
    }
    
    $output .= '</tbody></table>';

    // Add legend for today's date
    $output .= '
    <div style="margin-top: 15px; font-family: SolaimanLipi, Arial, sans-serif;">
        <span style="display: inline-block; width: 20px; height: 20px; background-color: #e8f5e9; border: 1px solid #dee2e6; vertical-align: middle;"></span>
        <span style="vertical-align: middle; margin-left: 5px;">' . esc_html__('আজকের তারিখ', 'ramadan-calendar-bangladesh') . '</span>
    </div>';

    return $output;
}

function mjashik_ramadan_enqueue_scripts() {
    wp_enqueue_style('solaiman-lipi', 'https://fonts.maateen.me/solaiman-lipi/font.css', array(), '1.0.0');
    wp_enqueue_style('ramadan-calendar-style', plugins_url('css/style.css', __FILE__), array('solaiman-lipi'), '1.1');
}
add_action('wp_enqueue_scripts', 'mjashik_ramadan_enqueue_scripts');

add_shortcode('mjashik_ramadan_time', 'mjashik_ramadan_time_function');
add_shortcode('mjashik_ramadan_calendar', 'mjashik_ramadan_calendar_shortcode');

register_activation_hook(__FILE__, 'mjashik_ramadan_activate');

function mjashik_ramadan_activate() {
    flush_rewrite_rules();
}

register_deactivation_hook(__FILE__, 'mjashik_ramadan_deactivate');

function mjashik_ramadan_deactivate() {
    flush_rewrite_rules();
}

class Mjashik_Ramadan_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'mjashik_ramadan_widget',
            'Ramadan Times',
            array('description' => 'Displays Ramadan prayer times')
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        if (!empty($instance['title'])) {
            echo $args['before_title'] . esc_html(apply_filters('widget_title', $instance['title'])) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        echo do_shortcode('[mjashik_ramadan_time]');
        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
    

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Ramadan Times', 'ramadan-calendar-bangladesh');
        ?>
<p>
    <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'ramadan-calendar-bangladesh'); ?></label>
    <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
        name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
        value="<?php echo esc_attr($title); ?>">
</p>
<?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? wp_strip_all_tags($new_instance['title']) : '';
        return $instance;
    }
}

function mjashik_register_ramadan_widget() {
    register_widget('Mjashik_Ramadan_Widget');
}
add_action('widgets_init', 'mjashik_register_ramadan_widget');