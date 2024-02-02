var country_code = ''; 
var response = '';
var ip2c = '';
// Try to get the country code with the free API provided by the server
function action_ip2c() {            
    var ip = '';
    var endpoint = '';
    var field = '';
    var type_response = '';

    function get_response(data) {
        if (type_response == 'json')
            try {
                response = JSON.parse(data);            
                country_code = response[field];
            } catch (error) {
                console.error("JSON Error: ", error);
            }
        else 
            country_code = data;
        
        // calls a function to do something, for example to display a message.
        // callback function is in global varible : ip2c_callback
        if (typeof ip2c_callback !== 'undefined' && ip2c_callback !== null) {
            if (typeof window[ip2c_callback] === 'function') {
                window[ip2c_callback]();
            } else {
                console.log('ip2c_callback: notfunc!');
            }
        } else {
            console.log('ip2c_callback: undefined!');
        }  
    }

    try {        
        if (typeof ip2c_api == 'undefined')
            return;

        var api = JSON.parse(ip2c_api);

        endpoint = api.endpoint;
        field = api.field;
        type_response = api.type_response;
        ip = api.ip;
        ip2c = api.ip;

        fetch(endpoint)
        .then(response => response.text())
        .then(data => get_response(data))
        .catch((error) => {
            console.error('Error fetch API:', error);
        });                 
        
    } catch (error) {
        console.error("JSON Error: ", error);
    }       
}    
document.addEventListener("DOMContentLoaded", function() {
    action_ip2c();
});