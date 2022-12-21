# Set.or.th API

a WordPress plugin that retrieves and displays data about a particular company's stock information from the SET (Stock Exchange of Thailand) API. 

The plugin has several functions that are hooked to WordPress actions and filters, allowing it to add a menu page to the WordPress administration dashboard, register settings, and define shortcodes that can be used to display the stock information in the front end of the website.

The plugin has an action hook called "set_api_retrieve_data" which appears to be responsible for retrieving the data from the SET API. This hook is triggered by the WordPress "wp_cron" action, which runs scheduled tasks in the background. When the hook is called, it checks if a company name has been set in the plugin's settings and, if so, retrieves the stock data for that company from the SET API using a URL with the company name appended to it. The data is then stored as options in the WordPress database using the "update_option" function.

Finally, the plugin defines several shortcodes that can be used to display the stock data in the front end of the website. Each shortcode function returns a string with a placeholder element (e.g. `<div class="set-api-last"></div>`) that can be styled with CSS and populated with the relevant data using JavaScript. The shortcodes can be used in posts, pages, and widgets to display the stock data in various locations on the website.