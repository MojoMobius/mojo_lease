var timer;

function display_message(message,color)
{
    clearTimeout(timer);
    var message_id;
    try{
        if(document.getElementById("content_div").style.display != "none"){
            message_id = "content_message";
        }
        else{
            message_id = "javascript_message";
        }
    }
    catch(e){
        message_id = "javascript_message";
    }

    if(message != undefined){
        message = message.replace(/\s/g,"&nbsp;");

        if(color == "green"){
            document.getElementById(message_id).style.color = "green";
        }
        else{
            document.getElementById(message_id).style.color = "red";
        }
        message = (message == "") ? "&nbsp;" : message;
    
        document.getElementById(message_id).innerHTML = message;
        document.getElementById(message_id).scrollIntoView(true);
        timer = setTimeout('hide_message()',10000 );
    }
}
function HtmlEncode(s)
{
  var el = document.createElement("div");
  el.innerText = el.textContent = s;
  s = el.innerHTML;
  return s;
}

function hide_message()
{
    var message_id;
    try{
        if(document.getElementById("content_div").style.display != "none"){
            message_id = "content_message";
        }
        else{
            message_id = "javascript_message";
        }
    }
    catch(e){
        message_id = "javascript_message";
    }

    document.getElementById(message_id).innerHTML = "&nbsp;";
}

/**
 * Trims the specified character in the beginning and ending of a string. If the character is not specified spaces will be trimmed.
 *
 * @param sString. The string to be trimmed.
 * @param trim_character. The character to be removed.
 * @return string. Returns the trimmed string.
 *
 */

function trimAll(sString,trim_character)
{
    if(trim_character == undefined || trim_character == ""){
        trim_character = " ";
    }
    while (sString.substring(0,1) == trim_character)
    {
        sString = sString.substring(1, sString.length);
    }
    while (sString.substring(sString.length-1, sString.length) == trim_character)
    {
        sString = sString.substring(0,sString.length-1);
    }
    return sString;
}
function AlphabetSpecialonly(field_name,value,allowed_characters,characters_not_allowed,alpha)
{
    //if(alpha!='yes')
    characters_not_allowed = characters_not_allowed;

	characters_allowed = allowed_characters+'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'+" ~`!@#$%^&*,()_-+=|\\}]{[\"':;?/>.<";
	
    //var value = document.getElementById(id).value;
    if(allowed_characters!='')
        var special_characters = allowed_characters;
    else
    var special_characters = " ~`!@#$%^&*,()_-+=|\\}]{[\"':;?/>.<";
    var number_allowed = isNaN(parseInt(characters_not_allowed));
   
    var flag = 0;
    var message;
    for(var i=0;i<special_characters.length;i++){
        if(characters_not_allowed.indexOf(special_characters.charAt(i)) != -1){
            special_characters = special_characters.replace(special_characters.charAt(i),"","g");
            i--;
        }
    }
    for(i=0;i<value.length;i++){
        if(characters_not_allowed.indexOf(value.charAt(i)) != -1 || (!isNaN(value.charAt(i)) && value.charAt(i) != " ")){
            flag = 1;
        }
    }
	for(i=0;i<value.length;i++){
		if(characters_allowed.indexOf(value.charAt(i)) == -1 || (!isNaN(value.charAt(i)) && value.charAt(i) != " ")){
            flag = 1;
        }
    }
    if(flag == 1){
        special_characters = special_characters.split("").join(",");
        special_characters = special_characters.replace(",,,",",comma,","g");
        special_characters = special_characters.replace(/,,$/,",comma","g");
        special_characters = special_characters.replace(/^,,/,"comma,","g");
        special_characters = special_characters.replace(" ","spaces");
        special_characters = special_characters.replace("~","tilde");
        special_characters = special_characters.replace("`","grave accent");
        special_characters = special_characters.replace("@","at symobl");
        special_characters = special_characters.replace("#","number sign");
        special_characters = special_characters.replace("$","dollar sign");
        special_characters = special_characters.replace("%","percent sign");
        special_characters = special_characters.replace("^","caret");
        special_characters = special_characters.replace("&","ampersand");
        special_characters = special_characters.replace("*","asterisk");
        special_characters = special_characters.replace("(","open paranthesis");
        special_characters = special_characters.replace(")","close paranthesis");
        special_characters = special_characters.replace("_","underscore");
        special_characters = special_characters.replace("-","hyphen");
        special_characters = special_characters.replace("+","plus sign");
        special_characters = special_characters.replace("=","equal sign");
        special_characters = special_characters.replace("|","vertical bar");
        special_characters = special_characters.replace("\\","backslash");
        special_characters = special_characters.replace("{","opening brace");
        special_characters = special_characters.replace("}","closing brace");
        special_characters = special_characters.replace("[","opening bracket");
        special_characters = special_characters.replace("]","closing bracket");
        special_characters = special_characters.replace("\"","double quotes");
        special_characters = special_characters.replace("'","single quote");
        special_characters = special_characters.replace(":","colon");
        special_characters = special_characters.replace(";","semicolon");
        special_characters = special_characters.replace("?","question mark");
        special_characters = special_characters.replace("/","slash");
        special_characters = special_characters.replace("<","less than symbol");
        special_characters = special_characters.replace(">","greater than symbol");
        special_characters = special_characters.replace(".","period");
        special_characters = special_characters.replace(/,([a-zA-Z\s]+)$/,' and $1');
        special_characters = (special_characters != "") ? ","+special_characters : "";
         number_allowed=true;
        if(alpha=='yes')
        {
        number_allowed=false;    
        }
        message = (number_allowed == true ) ? "Only alphabets "+special_characters+" are allowed " : "Only Alpha Numerics"+special_characters+ " are allowed ";
        alert(message)
        document.getElementById(field_name).value='';
        setTimeout(function() { document.getElementById(field_name).focus(); }, 10);
        
        return false;
    }
    else{
        return true;
    }
}
function trimString(sString,trim_string,position)
{
    if(trim_string == undefined || trim_string == ""){
        trim_string = "&nbsp;";
    }
    if(position == undefined || position == ""){
        position = "left";
    }

    if(position == "left" || position == "all"){
        while(sString.indexOf(trim_string) == 0){
            sString = sString.substring(trim_string.length,sString.length);
        }
    }
    if (position == "right" || position == "all"){
        while(sString.indexOf(trim_string) == sString.length - trim_string.length){
            sString = sString.substring(0,sString.indexOf(trim_string));
        }
    }

    return sString;
}

/**
 * checks the email whether the format of the supplied is correct and displays message if the email is invalid.
 *
 * @param email_address. The email to be checked
 * @param field_name. The field name where the email is input.
 * @return boolean. Returns true if the email is valid; false otherwise.
 *
 */
// Mandatory
function MandatoryValue(field_name,val,disp_name) {
    if(document.getElementById(field_name).value==''){
            alert(disp_name+" is Mandatory");
            setTimeout(function() { document.getElementById(field_name).focus(); }, 10);
            return false;
    }
    return true;
}
// Only Alphabets & Space
function AlphabetOnly(field_name,val) {
    var reg=/^[a-zA-Z\s]+$/;
    if(document.getElementById(field_name).value!=''){
        if(reg.test(val) == false) {
            alert("Alphabets with space only allowed.");
            document.getElementById(field_name).value='';
            setTimeout(function() { document.getElementById(field_name).focus(); }, 10);
            return false;
        }
    }
    return true;
}
// Only numbers & Space
function NumbersOnly(field_name,val) {
    //var reg=/^[a-zA-Z\s]+$/;
    var reg=/^[0-9]*$/;
    if(document.getElementById(field_name).value!=''){
        if(reg.test(val) == false) {
            alert("Numbers only allowed.");
            document.getElementById(field_name).value='';
            setTimeout(function() { document.getElementById(field_name).focus(); }, 10);
            return false;
        }
    }
    return true;
}
// Only numbers & Space
function AlphaNumericOnly(field_name,val) {
    //var reg=/^[a-zA-Z\s]+$/;
    var reg=/^[a-zA-Z0-9]*$/;
    if(document.getElementById(field_name).value!=''){
        if(reg.test(val) == false) {
            alert("Numbers & Alphabets only allowed.");
            document.getElementById(field_name).value='';
            setTimeout(function() { document.getElementById(field_name).focus(); }, 10);
            return false;
        }
    }
    return true;
}
/* function AlphaNumericEmail(field_name,val) {
    //var reg=/^[a-zA-Z\s]+$/;
   // var reg=/^[a-zA-Z0-9]*$/;
   var reg = /^([_a-zA-Z0-9\'-]+(\.[_a-zA-Z0-9\'-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.((\w{2}\.\w{2})|(com|net|org|edu|int|mil|gov|arpa|in|biz|aero|name|coop|co|info|pro|museum)|(in))|[a-zA-Z0-9]+)$/;
    if(document.getElementById(field_name).value!=''){
        if(reg.test(val) == false) {
            alert("Alphabets or numeric or email only allowed or enter valid email address");
            document.getElementById(field_name).value='';
            setTimeout(function() { document.getElementById(field_name).focus(); }, 10);
            return false;
        }
    }
    return true;
} */
// Only numbers & Space
function UrlOnly(field_name,val) {
    //var reg=/^[a-zA-Z\s]+$/;
    var reg=/^(www\.)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
    if(document.getElementById(field_name).value!=''){
        if(reg.test(val) == false) {
            alert("Enter Valid Url.");
            document.getElementById(field_name).value='';
            setTimeout(function() { document.getElementById(field_name).focus(); }, 10);
            return false;
        }
    }
    return true;
}
function UrlOnly_HandsOn(value,callback) {
    //var reg=/^[a-zA-Z\s]+$/;
    var reg=/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
    //if(document.getElementById(field_name).value!=''){
        if(reg.test(value) == false) {
            alert("Enter Valid Url.");
            callback(false);
           // document.getElementById(field_name).value='';
          //  setTimeout(function() { document.getElementById(field_name).focus(); }, 10);
            
        }
         callback(true);
    //}
    return true;
}
function EmailOnly(field_name,email_address)
{
//    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
 var reg = /^\w+([\.\-_]?\w+)*@\w+([-]?\w+)*\.((\w{2}\.\w{2})|(com|net|org|edu|int|mil|gov|arpa|in|biz|aero|name|coop|co|info|pro|museum)|(in))$/;
    if(document.getElementById(field_name).value!=''){
    if(reg.test(email_address) == false) {
        alert("Email is invalid");
        //document.getElementById(field_name).value='';
        setTimeout(function() { document.getElementById(field_name).focus(); }, 10);
        return false;
    }
    }
    return true;
}
//allow digit with comma alone
function check_number_comma(field_value,field_name)
{
  var reg = /^\d+(,\d+)*$/;
    if(document.getElementById(field_name).value!=''){
    if(reg.test(field_value) == false) {
        alert("Digits with comma only allowed.");
        document.getElementById(field_name).value='';
        setTimeout(function() { document.getElementById(field_name).focus(); }, 10);
        return false;
    }
    }
    return true;  
}
//allow decimal with comma and  alone
function check_decimal_comma(field_value,field_name,errormsg)
{
    // used var reg=/^\s*?([\d\,]+(\.\d{1,2})?|\.\d{1,2})\s*$/;
    //^-?\d*\.?\d+$
    //var reg=/^((\d?)|((\d+\.?\d*)|(\d*\.?\d+))|((\d+\.?\d*\,\ ?)*(\d+\.?\d*))|((\d*\.?\d+\,\ ?)*(\d*\.?\d+))|((\d+\.?\d*\,\ ?)*(\d*\.?\d+))|((\d*\.?\d+\,\ ?)*(\d+\.?\d*)))$/;
    var reg=/^((-?\d?)|((-?\d+\.?\d*)|(-?\d*\.?\d+))|((-?\d+\.?\d*\,\ ?)*(-?\d+\.?\d*))|((-?\d*\.?\d+\,\ ?)*(-?\d*\.?\d+))|((-?\d+\.?\d*\,\ ?)*(-?\d*\.?\d+))|((-?\d*\.?\d+\,\ ?)*(-?\d+\.?\d*)))$/;
    if(document.getElementById(field_name).value!=''){
     if(reg.test(field_value) == false) {
        alert("Numbers,Decimal values and comma only allowed in "+errormsg);
        document.getElementById(field_name).value='';
         //document.getElementById('InspectionCompany').focus();
         setTimeout(function() { document.getElementById(field_name).focus(); }, 10);
        //document.getElementById(field_name).focus();
        return false;
    }
}
    return true;  
}
/*
 *
 * Checks the special characters in a particular element and display_messages if any invalid characters exists.
 *
 * @param id Id of the element in which special characters to be checked
 * @param characters_not_allowed Characters that are not allowed in the id element.
 * @return boolean Returns true if characters_not_allowed is found in the value else false.
 *
 *
 */
 
function SpecialOnly(field_name,value,allowed_characters,characters_not_allowed,alpha){
	
	characters_not_allowed = characters_not_allowed+'1234567890'+'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	characters_allowed = allowed_characters+" ~`!@#$%^&*,()_-+=|\\}]{[\"':;?/>.<";
	
	 if(allowed_characters!='')
		   var special_characters = allowed_characters;
	  else
    var special_characters = " ~`!@#$%^&*,()_-+=|\\}]{[\"':;?/>.<";
    var number_allowed = isNaN(parseInt(characters_not_allowed));

    var flag = 0;
    var message;
    for(var i=0;i<special_characters.length;i++){
        if(characters_not_allowed.indexOf(special_characters.charAt(i)) != -1){
            special_characters = special_characters.replace(special_characters.charAt(i),"","g");
            i--;
        }
    }

    for(i=0;i<value.length;i++){
		if(characters_not_allowed.indexOf(value.charAt(i)) != -1 || (!isNaN(value.charAt(i)) && number_allowed == false && value.charAt(i) != " ")){          
		   flag = 1;
        }
    }
	for(i=0;i<value.length;i++){
		if(characters_allowed.indexOf(value.charAt(i)) == -1 || (!isNaN(value.charAt(i)) && number_allowed == false && value.charAt(i) != " ")){
            flag = 1;
        }
    }
	 if(flag == 1){
        special_characters = special_characters.split("").join(",");
        special_characters = special_characters.replace(",,,",",comma,","g");
        special_characters = special_characters.replace(/,,$/,",comma","g");
        special_characters = special_characters.replace(/^,,/,"comma,","g");
        special_characters = special_characters.replace(" ","spaces");
        special_characters = special_characters.replace("~","tilde");
        special_characters = special_characters.replace("`","grave accent");
        special_characters = special_characters.replace("@","at symobl");
        special_characters = special_characters.replace("#","number sign");
        special_characters = special_characters.replace("$","dollar sign");
        special_characters = special_characters.replace("%","percent sign");
        special_characters = special_characters.replace("^","caret");
        special_characters = special_characters.replace("&","ampersand");
        special_characters = special_characters.replace("*","asterisk");
        special_characters = special_characters.replace("(","open paranthesis");
        special_characters = special_characters.replace(")","close paranthesis");
        special_characters = special_characters.replace("_","underscore");
        special_characters = special_characters.replace("-","hyphen");
        special_characters = special_characters.replace("+","plus sign");
        special_characters = special_characters.replace("=","equal sign");
        special_characters = special_characters.replace("|","vertical bar");
        special_characters = special_characters.replace("\\","backslash");
        special_characters = special_characters.replace("{","opening brace");
        special_characters = special_characters.replace("}","closing brace");
        special_characters = special_characters.replace("[","opening bracket");
        special_characters = special_characters.replace("]","closing bracket");
        special_characters = special_characters.replace("\"","double quotes");
        special_characters = special_characters.replace("'","single quote");
        special_characters = special_characters.replace(":","colon");
        special_characters = special_characters.replace(";","semicolon");
        special_characters = special_characters.replace("?","question mark");
        special_characters = special_characters.replace("/","slash");
        special_characters = special_characters.replace("<","less than symbol");
        special_characters = special_characters.replace(">","greater than symbol");
        special_characters = special_characters.replace(".","period");
        special_characters = special_characters.replace(/,([a-zA-Z\s]+)$/,' and $1');
        special_characters = (special_characters != "") ? special_characters : "";
         number_allowed=true;
        if(alpha=='yes')
        {
        number_allowed=false;    
        }
        message = (number_allowed == true ) ? "Only "+special_characters+" are allowed " : "Only Alpha Numerics"+special_characters+ " are allowed ";
        alert(message)
        document.getElementById(field_name).value='';
        setTimeout(function() { document.getElementById(field_name).focus(); }, 10);
        
        return false;
    }
    else{
        return true;
    }
	
}
function AlphaNumericSpecial(field_name,value,allowed_characters,characters_not_allowed,alpha)
{
	alpha = 'yes'
	characters_not_allowed = characters_not_allowed;

	characters_allowed = allowed_characters+'1234567890'+'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'+" ~`!@#$%^&*,()_-+=|\\}]{[\"':;?/>.<";
	
    //var value = document.getElementById(id).value;
	 if(allowed_characters!='')
		   var special_characters = allowed_characters;
	  else
    var special_characters = " ~`!@#$%^&*,()_-+=|\\}]{[\"':;?/>.<";
    var number_allowed = isNaN(parseInt(characters_not_allowed));

    var flag = 0;
    var message;
    for(var i=0;i<special_characters.length;i++){
        if(characters_not_allowed.indexOf(special_characters.charAt(i)) != -1){
            special_characters = special_characters.replace(special_characters.charAt(i),"","g");
            i--;
        }
    }
	
    for(i=0;i<value.length;i++){
		if(characters_not_allowed.indexOf(value.charAt(i)) != -1 || (!isNaN(value.charAt(i)) && number_allowed == false && value.charAt(i) != " ")){
            flag = 1;
        }
    }
	for(i=0;i<value.length;i++){
		if(characters_allowed.indexOf(value.charAt(i)) == -1 || (!isNaN(value.charAt(i)) && number_allowed == false && value.charAt(i) != " ")){
            flag = 1;
        }
    }
	
	
    /* alpha = 'yes'
    if(alpha!='yes')
    characters_not_allowed = characters_not_allowed+'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //var value = document.getElementById(id).value;
    var special_characters = " ~`!@#$%^&*,()_-+=|\\}]{[\"':;?/>.<";
    var number_allowed = isNaN(parseInt(characters_not_allowed));
   
    var flag = 0;
    var message;
    for(var i=0;i<special_characters.length;i++){
        if(characters_not_allowed.indexOf(special_characters.charAt(i)) != -1){
            special_characters = special_characters.replace(special_characters.charAt(i),"","g");
            i--;
        }
    }
    for(i=0;i<value.length;i++){
        if(characters_not_allowed.indexOf(value.charAt(i)) != -1 || (!isNaN(value.charAt(i)) && number_allowed == false && value.charAt(i) != " ")){
            flag = 1;
        }
    } */
    if(flag == 1){
        special_characters = special_characters.split("").join(",");
        special_characters = special_characters.replace(",,,",",comma,","g");
        special_characters = special_characters.replace(/,,$/,",comma","g");
        special_characters = special_characters.replace(/^,,/,"comma,","g");
        special_characters = special_characters.replace(" ","spaces");
        special_characters = special_characters.replace("~","tilde");
        special_characters = special_characters.replace("`","grave accent");
        special_characters = special_characters.replace("@","at symobl");
        special_characters = special_characters.replace("#","number sign");
        special_characters = special_characters.replace("$","dollar sign");
        special_characters = special_characters.replace("%","percent sign");
        special_characters = special_characters.replace("^","caret");
        special_characters = special_characters.replace("&","ampersand");
        special_characters = special_characters.replace("*","asterisk");
        special_characters = special_characters.replace("(","open paranthesis");
        special_characters = special_characters.replace(")","close paranthesis");
        special_characters = special_characters.replace("_","underscore");
        special_characters = special_characters.replace("-","hyphen");
        special_characters = special_characters.replace("+","plus sign");
        special_characters = special_characters.replace("=","equal sign");
        special_characters = special_characters.replace("|","vertical bar");
        special_characters = special_characters.replace("\\","backslash");
        special_characters = special_characters.replace("{","opening brace");
        special_characters = special_characters.replace("}","closing brace");
        special_characters = special_characters.replace("[","opening bracket");
        special_characters = special_characters.replace("]","closing bracket");
        special_characters = special_characters.replace("\"","double quotes");
        special_characters = special_characters.replace("'","single quote");
        special_characters = special_characters.replace(":","colon");
        special_characters = special_characters.replace(";","semicolon");
        special_characters = special_characters.replace("?","question mark");
        special_characters = special_characters.replace("/","slash");
        special_characters = special_characters.replace("<","less than symbol");
        special_characters = special_characters.replace(">","greater than symbol");
        special_characters = special_characters.replace(".","period");
        special_characters = special_characters.replace(/,([a-zA-Z\s]+)$/,' and $1');
        special_characters = (special_characters != "") ? ","+special_characters : "";
         number_allowed=true;
        if(alpha=='yes')
        {
        number_allowed=false;    
        }
        message = (number_allowed == true ) ? "Only Numbers"+special_characters+" are allowed " : "Only Alpha Numerics"+special_characters+ " are allowed ";
        alert(message)
        document.getElementById(field_name).value='';
        setTimeout(function() { document.getElementById(field_name).focus(); }, 10);
        
        return false;
    }
    else{
        return true;
    }
}
function NumericSpecialOnly(field_name,value,allowed_characters,characters_not_allowed,alpha)
{  
   // if(alpha!='yes')
    characters_not_allowed = characters_not_allowed+'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	characters_allowed = allowed_characters+'1234567890'+" ~`!@#$%^&*,()_-+=|\\}]{[\"':;?/>.<";
	
    //var value = document.getElementById(id).value;
	 if(allowed_characters!='')
		   var special_characters = allowed_characters;
	  else
    var special_characters = " ~`!@#$%^&*,()_-+=|\\}]{[\"':;?/>.<";
    var number_allowed = isNaN(parseInt(characters_not_allowed));

    var flag = 0;
    var message;
    for(var i=0;i<special_characters.length;i++){
        if(characters_not_allowed.indexOf(special_characters.charAt(i)) != -1){
            special_characters = special_characters.replace(special_characters.charAt(i),"","g");
            i--;
        }
    }
	
    for(i=0;i<value.length;i++){
		if(characters_not_allowed.indexOf(value.charAt(i)) != -1 || (!isNaN(value.charAt(i)) && number_allowed == false && value.charAt(i) != " ")){
            flag = 1;
        }
    }
	for(i=0;i<value.length;i++){
		if(characters_allowed.indexOf(value.charAt(i)) == -1 || (!isNaN(value.charAt(i)) && number_allowed == false && value.charAt(i) != " ")){
            flag = 1;
        }
    }
	
    if(flag == 1){
        special_characters = special_characters.split("").join(",");
        special_characters = special_characters.replace(",,,",",comma,","g");
        special_characters = special_characters.replace(/,,$/,",comma","g");
        special_characters = special_characters.replace(/^,,/,"comma,","g");
        special_characters = special_characters.replace(" ","spaces");
        special_characters = special_characters.replace("~","tilde");
        special_characters = special_characters.replace("`","grave accent");
        special_characters = special_characters.replace("@","at symobl");
        special_characters = special_characters.replace("#","number sign");
        special_characters = special_characters.replace("$","dollar sign");
        special_characters = special_characters.replace("%","percent sign");
        special_characters = special_characters.replace("^","caret");
        special_characters = special_characters.replace("&","ampersand");
        special_characters = special_characters.replace("*","asterisk");
        special_characters = special_characters.replace("(","open paranthesis");
        special_characters = special_characters.replace(")","close paranthesis");
        special_characters = special_characters.replace("_","underscore");
        special_characters = special_characters.replace("-","hyphen");
        special_characters = special_characters.replace("+","plus sign");
        special_characters = special_characters.replace("=","equal sign");
        special_characters = special_characters.replace("|","vertical bar");
        special_characters = special_characters.replace("\\","backslash");
        special_characters = special_characters.replace("{","opening brace");
        special_characters = special_characters.replace("}","closing brace");
        special_characters = special_characters.replace("[","opening bracket");
        special_characters = special_characters.replace("]","closing bracket");
        special_characters = special_characters.replace("\"","double quotes");
        special_characters = special_characters.replace("'","single quote");
        special_characters = special_characters.replace(":","colon");
        special_characters = special_characters.replace(";","semicolon");
        special_characters = special_characters.replace("?","question mark");
        special_characters = special_characters.replace("/","slash");
        special_characters = special_characters.replace("<","less than symbol");
        special_characters = special_characters.replace(">","greater than symbol");
        special_characters = special_characters.replace(".","period");
        special_characters = special_characters.replace(/,([a-zA-Z\s]+)$/,' and $1');
        special_characters = (special_characters != "") ? ","+special_characters : "";
         number_allowed=true;
        if(alpha=='yes')
        {
        number_allowed=false;    
        }
        message = (number_allowed == true ) ? "Only Numbers"+special_characters+" are allowed " : "Only Alpha Numerics"+special_characters+ " are allowed ";
        alert(message)		
        document.getElementById(field_name).value='';
        setTimeout(function() { document.getElementById(field_name).focus(); }, 10);
        
        return false;
    }
    else{
        return true;
    }
}

function make_nullandfocus(id)
{
    document.getElementById(id).value = "";
    document.getElementById(id).focus();
}
function make_focus(id)
{
    document.getElementById(id).focus();
}

function clear_value(event)
{
    var element_type = event.target.type;
    
    if(element_type == "text")
        event.target.value = "";
    else if(element_type == "select-one")
        event.target.selectedIndex = 0;
    else if(element_type == "textarea")
        event.target.innerHTML = "";
}

/*
 * Checks whether a value available in an array.
 *
 * @param needle. value to be searched.
 * @param haystack. array value in which searching has to be done.
 * @return boolean. returns true if the value is found in array; false otherwise.
 *
 */
function in_array(needle,haystack)
{
    for(var i=0;i<haystack.length;i++)
    {
        if(needle == haystack[i])
        {
            return true;
        }
    }
    return false;
}

function on_select_scroll(event)
{
    var target_id = event.target.id.replace("code","count");
    if(event.target.id == target_id){
        target_id = event.target.id.replace("count","code");
    }
    var target = document.getElementById(target_id);
    target.scrollTop = event.target.scrollTop;
}

function set_values(event)
{
    if(event.target.parentNode.type == "select-multiple" && event.target.selected){
        document.getElementById(event.target.parentNode.id.replace("code","count")+"_"+event.target.index).value = 1;
    }
    var select_options,textboxes;
    if(event.target.parentNode.type == "select-multiple"){
        select_options = event.target.parentNode.options;
        textboxes = document.getElementsByName(event.target.parentNode.id.replace("code","count")+"s")
    }
    else{
        select_options = document.getElementById(event.target.offsetParent.id.replace("count","code")).options;
        textboxes = document.getElementsByName(event.target.offsetParent.id+"s");
    }
    for(var i=0;i<select_options.length;i++){
        if(select_options[i].selected && (!_isInteger(textboxes[i].value) || textboxes[i].value == "0" || trimAll(textboxes[i].value) == "")){
            display_message("Please enter a valid round number");
            textboxes[i].focus();
            return;
        }
        else if(!select_options[i].selected){
            textboxes[i].value = "0";
        }
    }
}

function create_ajax_object()
{	
    var xmlHttp;
    if(window.ActiveXObject){
        try{
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch (e){
            xmlHttp = false;
        }
    }
    else{
        try{
            xmlHttp = new XMLHttpRequest();
        }
        catch (e){
            xmlHttp = false;
        }
    }
    
    if (!xmlHttp){
        alert("Error creating the XMLHttpRequest object.");
        return false;
    }
    return xmlHttp;
}

function show_calendar(textbox_id,image_id,show_time)
{
    date_format = "%d-%b-%Y";
	
    var cal = new Mobius.Calendar.setup({
        inputField: textbox_id,
        step: 1,
        ifFormat: date_format,
        button: image_id,
        showsTime: show_time
    });
}



/**
 *
 * HISTORY
 * May 17, 2003: Fixed bug in parseDate() for dates <1970
 * March 11, 2003: Added parseDate() function
 * March 11, 2003: Added "NNN" formatting option. Doesn't match up
 * perfectly with SimpleDateFormat formats, but
 * backwards-compatability was required.
 * ------------------------------------------------------------------
 * These functions use the same 'format' strings as the
 * java.text.SimpleDateFormat class, with minor exceptions.
 * The format string consists of the following abbreviations:
 * Field        | Full Form          | Short Form
 * -------------+--------------------+-----------------------
 * Year         | yyyy (4 digits)    | yy (2 digits), y (2 or 4 digits)
 * Month        | MMM (name or abbr.)| MM (2 digits), M (1 or 2 digits)
 *              | NNN (abbr.)        |
 * Day of Month | dd (2 digits)      | d (1 or 2 digits)
 * Day of Week  | EE (name)          | E (abbr)
 * Hour (1-12)  | hh (2 digits)      | h (1 or 2 digits)
 * Hour (0-23)  | HH (2 digits)      | H (1 or 2 digits)
 * Hour (0-11)  | KK (2 digits)      | K (1 or 2 digits)
 * Hour (1-24)  | kk (2 digits)      | k (1 or 2 digits)
 * Minute       | mm (2 digits)      | m (1 or 2 digits)
 * Second       | ss (2 digits)      | s (1 or 2 digits)
 * AM/PM        | a                  |
 * NOTE THE DIFFERENCE BETWEEN MM and mm! Month=MM, not mm!
 * Examples:
 * "MMM d, y" matches: January 01, 2000
 *                     Dec 1, 1900
 *                     Nov 20, 00
 *                     "M/d/yy"   matches: 01/20/00
 *                     9/2/00
 * "MMM dd, yyyy hh:mm:ssa" matches: "January 01, 2000 12:30:45AM"
 */

var MONTH_NAMES=new Array('January','February','March','April','May','June','July','August','September','October','November','December','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
var DAY_NAMES=new Array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sun','Mon','Tue','Wed','Thu','Fri','Sat');
function LZ(x) {
    return(x<0||x>9?"":"0")+x
    }



/**
 *
 * isDate ( date_string, format_string )
 * Returns true if date string matches format of format string and
 * is a valid date. Else returns false.
 * It is recommended that you trim whitespace around the value before
 * passing it to this function, as whitespace is NOT ignored!
 *
 */
function isDate(id,val,allowed,notallow,format) {
    if(val!=''){
    var date=getDateFromFormat(val,format);
   
    if (date==0) {
        alert('Invalid Date format');
        setTimeout(function() { document.getElementById(id).focus(); }, 10);
       
        return false;
    }
    return true;
}
}

/*
 *
 * Compare two date strings to see which is greater.
 *
 * @param date1
 * @param date2
 * @return 1 if date1 is greater than date2; 0 if date1 is lesser than date2 of if they are the same; -1 if either of the dates is in an invalid format
 *
 */

function compareDates(date1,date2)
{
    var dateformat = 'yyyy-MM-dd';
    var d1=getDateFromFormat(date1,dateformat);
    var d2=getDateFromFormat(date2,dateformat);

    if (d1==0 || d2==0)
    {
        return -1;
    }
    if (d1 > d2)
    {
        return 1;
    }
    /*if (d1 == d2) {
        return 2;
    }*/
    return 0;
}

function findDifference(date1,date2)
{
    var dateformat = 'yyyy-MM-dd';
    var d1=getDateFromFormat(date1,dateformat);
    var d2=getDateFromFormat(date2,dateformat);

    difference = parseInt(((d1-d2)/86400000)+1);
    return difference;
}
/**
 * formatDate (date_object, format)
 * Returns a date in the output format specified.
 * The format string uses the same abbreviations as in getDateFromFormat()
 *
 */
function formatDate(date,format)
{
    format=format+"";
    var result="";
    var i_format=0;
    var c="";
    var token="";
    var y=date.getYear()+"";
    var M=date.getMonth()+1;
    var d=date.getDate();
    var E=date.getDay();
    var H=date.getHours();
    var m=date.getMinutes();
    var s=date.getSeconds();
    var yyyy,yy,MMM,MM,dd,hh,h,mm,ss,ampm,HH,KK,K,kk,k;
    // Convert real date parts into formatted versions

    var value=new Object();
    if (y.length < 4) {
        y=""+(y-0+1900);
    }
    value["y"]=""+y;
    value["yyyy"]=y;
    value["yy"]=y.substring(2,4);
    value["M"]=M;
    value["MM"]=LZ(M);
    value["MMM"]=MONTH_NAMES[M-1];
    value["NNN"]=MONTH_NAMES[M+11];
    value["d"]=d;
    value["dd"]=LZ(d);
    value["E"]=DAY_NAMES[E+7];
    value["EE"]=DAY_NAMES[E];
    value["H"]=H;
    value["HH"]=LZ(H);
    if (H==0){
        value["h"]=12;
    }
    else if (H>12){
        value["h"]=H-12;
    }
    else {
        value["h"]=H;
    }
    value["hh"]=LZ(value["h"]);
    if (H>11){
        value["K"]=H-12;
    } else {
        value["K"]=H;
    }
    value["k"]=H+1;
    value["KK"]=LZ(value["K"]);
    value["kk"]=LZ(value["k"]);
    if (H > 11) {
        value["a"]="PM";
    }
    else {
        value["a"]="AM";
    }
    value["m"]=m;
    value["mm"]=LZ(m);
    value["s"]=s;
    value["ss"]=LZ(s);
    while (i_format < format.length) {
        c=format.charAt(i_format);
        token="";
        while ((format.charAt(i_format)==c) && (i_format < format.length)) {
            token += format.charAt(i_format++);
        }
        if (value[token] != null) {
            result=result + value[token];
        }
        else {
            result=result + token;
        }
    }

    return result;
}

/**
  *  Utility functions for parsing in getDateFromFormat()
  */
function _isInteger(val) {
    var digits="1234567890";
    for (var i=0; i < val.length; i++) {
        if (digits.indexOf(val.charAt(i))==-1) {
            return false;
        }
    }
    return true;
}
function _getInt(str,i,minlength,maxlength) {
    for (var x=maxlength; x>=minlength; x--) {
        var token=str.substring(i,i+x);
        if (token.length < minlength) {
            return null;
        }
        if (_isInteger(token)) {
            return token;
        }
    }
    return null;
}

/**
 * getDateFromFormat( date_string , format_string )
 *
 * This function takes a date string and a format string. It matches
 * If the date string matches the format string, it returns the
 * getTime() of the date. If it does not match, it returns 0.
 */
function getDateFromFormat(val,format) {
    val=val+"";
    format=format+"";
    var i_val=0;
    var i_format=0;
    var c="";
    var token="";
    var token2="";
    var x,y;
    var now=new Date();
    var year=now.getYear();
    var month=now.getMonth()+1;
    var date=1;
    var hh=now.getHours();
    var mm=now.getMinutes();
    var ss=now.getSeconds();
    var ampm="";

    while (i_format < format.length) {
        // Get next token from format string
        c=format.charAt(i_format);
        token="";
        while ((format.charAt(i_format)==c) && (i_format < format.length)) {
            token += format.charAt(i_format++);
        }
        // Extract contents of value based on format token
        if(token=="yyyy" || token=="yy" || token=="y") {
            if (token=="yyyy") {
                x=4;
                y=4;
            }
            if (token=="yy")   {
                x=2;
                y=2;
            }
            if (token=="y")    {
                x=2;
                y=4;
            }
            year=_getInt(val,i_val,x,y);
            if (year==null) {
                return 0;
            }
			if (year=='0000' || year=='000' || year=='00' || year=='0') {
                return 0;
            }
            i_val += year.length;
            if (year.length==2) {
                if (year > 70) {
                    year=1900+(year-0);
                }
                else {
                    year=2000+(year-0);
                }
            }
        }
        else if (token=="MMM"||token=="NNN"){
            month=0;
            for (var i=0; i<MONTH_NAMES.length; i++) {
                var month_name=MONTH_NAMES[i];
                if (val.substring(i_val,i_val+month_name.length).toLowerCase()==month_name.toLowerCase()) {
                    if (token=="MMM"||(token=="NNN"&&i>11)) {
                        month=i+1;
                        if (month>12) {
                            month -= 12;
                        }
                        i_val += month_name.length;
                        break;
                    }
                }
            }
            if ((month < 1)||(month>12)){
                return 0;
            }
        }
        else if (token=="EE"||token=="E"){
            for (i=0; i<DAY_NAMES.length; i++) {
                var day_name=DAY_NAMES[i];
                if (val.substring(i_val,i_val+day_name.length).toLowerCase()==day_name.toLowerCase()) {
                    i_val += day_name.length;
                    break;
                }
            }
        }
        else if (token=="MM"||token=="M") {
            month=_getInt(val,i_val,token.length,2);
            if(month==null||(month<1)||(month>12)){
                return 0;
            }
            i_val+=month.length;
        }
        else if (token=="dd"||token=="d") {
            date=_getInt(val,i_val,token.length,2);
            if(date==null||(date<1)||(date>31)){
                return 0;
            }
            i_val+=date.length;
        }
        else if (token=="hh"||token=="h") {
            hh=_getInt(val,i_val,token.length,2);
            if(hh==null||(hh<1)||(hh>12)){
                return 0;
            }
            i_val+=hh.length;
        }
        else if (token=="HH"||token=="H") {
            hh=_getInt(val,i_val,token.length,2);
            if(hh==null||(hh<0)||(hh>23)){
                return 0;
            }
            i_val+=hh.length;
        }
        else if (token=="KK"||token=="K") {
            hh=_getInt(val,i_val,token.length,2);
            if(hh==null||(hh<0)||(hh>11)){
                return 0;
            }
            i_val+=hh.length;
        }
        else if (token=="kk"||token=="k") {
            hh=_getInt(val,i_val,token.length,2);
            if(hh==null||(hh<1)||(hh>24)){
                return 0;
            }
            i_val+=hh.length;
            hh--;
        }
        else if (token=="mm"||token=="m") {
            mm=_getInt(val,i_val,token.length,2);
            if(mm==null||(mm<0)||(mm>59)){
                return 0;
            }
            i_val+=mm.length;
        }
        else if (token=="ss"||token=="s") {
            ss=_getInt(val,i_val,token.length,2);
            if(ss==null||(ss<0)||(ss>59)){
                return 0;
            }
            i_val+=ss.length;
        }
        else if (token=="a") {
            if (val.substring(i_val,i_val+2).toLowerCase()=="am") {
                ampm="AM";
            }
            else if (val.substring(i_val,i_val+2).toLowerCase()=="pm") {
                ampm="PM";
            }
            else {
                return 0;
            }
            i_val+=2;
        }
        else {
            if (val.substring(i_val,i_val+token.length)!=token) {
                return 0;
            }
            else {
                i_val+=token.length;
            }
        }
    }
    // If there are any trailing characters left in the value, it doesn't match
    if (i_val != val.length) {
        return 0;
    }
    // Is date valid for month?
    if (month==2) {
        // Check for leap year
        if ( ( (year%4==0)&&(year%100 != 0) ) || (year%400==0) ) { // leap year
            if (date > 29){
                return 0;
            }
        }
        else {
            if (date > 28) {
                return 0;
            }
        }
}
if ((month==4)||(month==6)||(month==9)||(month==11)) {
    if (date > 30) {
        return 0;
    }
}
// Correct hours value
if (hh<12 && ampm=="PM") {
    hh=hh-0+12;
}
else if (hh>11 && ampm=="AM") {
    hh-=12;
}
var newdate=new Date(year,month-1,date,hh,mm,ss);
return newdate.getTime();
}

/**
 * parseDate( date_string [, prefer_euro_format] )
 *
 * This function takes a date string and tries to match it to a
 * number of possible date formats to get the value. It will try to
 * match against the following international formats, in this order:
 * y-M-d   MMM d, y   MMM d,y   y-MMM-d   d-MMM-y  MMM d
 * M/d/y   M-d-y      M.d.y     MMM-d     M/d      M-d
 * d/M/y   d-M-y      d.M.y     d-MMM     d/M      d-M
 * A second argument may be passed to instruct the method to search
 * for formats like d/M/y (european format) before M/d/y (American).
 * Returns a Date object or null if no patterns match.
 */
function parseDate(val) {
    var preferEuro=(arguments.length==2)?arguments[1]:false;
    generalFormats=new Array('y-M-d','MMM d, y','MMM d,y','y-MMM-d','d-MMM-y','MMM d');
    monthFirst=new Array('M/d/y','M-d-y','M.d.y','MMM-d','M/d','M-d');
    dateFirst =new Array('d/M/y','d-M-y','d.M.y','d-MMM','d/M','d-M');
    var checkList=new Array('generalFormats',preferEuro?'dateFirst':'monthFirst',preferEuro?'monthFirst':'dateFirst');
    var d=null;
    for (var i=0; i<checkList.length; i++) {
        var l=window[checkList[i]];
        for (var j=0; j<l.length; j++) {
            d=getDateFromFormat(val,l[j]);
            if (d!=0) {
                return new Date(d);
            }
        }
    }
    return null;
}

/**
 *	multiple file selector class
 */

function multiSelector(list_target)
{
    // Where to write the list
    this.list_target = list_target;

    //Add a new file input element

    this.addElement = function( element ){

        // Make sure it's a file input element
        if( element.tagName == 'INPUT' && element.type == 'file' ){

            // Element name -- what number am I?

            element.name = "screens[]";

            // Add reference to this object
            element.multi_selector = this;

            // What to do when a file is selected
            element.onchange = function(){

                var f3=element.value;
                var file3=f3.substring(f3.length - 4);
                ;

                if(file3==".jpg" || file3=="jpeg" || file3==".png" || file3==".gif" || file3==".bmp")
                {
                    var div_ct = document.getElementById("files_list").getElementsByTagName("div");

                    for(j=0;j<div_ct.length;j++)
                    {
                        div_val = div_ct[j].innerHTML.replace('<input value="Delete" type="button">',"");

                        if(div_val == f3)
                        {
                            alert("File is in upload queue");
                            element.value="";
                            return false;
                        }
                    }

                    // New file input
                    var new_element = document.createElement( 'input' );
                    new_element.type = 'file';
                    new_element.className = 'textboxfont';

                    // Add new element
                    this.parentNode.insertBefore( new_element, this );

                    // Apply 'update' to element
                    this.multi_selector.addElement( new_element );

                    // Update list
                    this.multi_selector.addListRow( this );

                    // Hide this: we can't use display:none because Safari doesn't like it
                    this.style.position = 'absolute';
                    this.style.left = '-1000px';
                }
                else
                {
                    alert("Invalid file");
                    element.value="";
                }
            };

        }

    };

    // Add a new row to the list of files

    this.addListRow = function( element )
    {

        // Row div
        var new_row = document.createElement( 'div' );

        // Delete button
        var new_row_button = document.createElement( 'input' );
        new_row_button.type = 'button';
        new_row_button.value = 'Delete';

        // References
        new_row.element = element;

        // Delete function
        new_row_button.onclick= function(){

            // Remove element from form
            this.parentNode.element.parentNode.removeChild( this.parentNode.element );

            // Remove this row from the list
            this.parentNode.parentNode.removeChild( this.parentNode );

            // Decrement counter
            this.parentNode.element.multi_selector.count--;

            // Appease Safari
            //    without it Safari wants to reload the browser window
            //    which nixes your already queued uploads
            return false;
        };

        // Set row value
        new_row.innerHTML = element.value;

        // Add button
        new_row.appendChild( new_row_button );

        // Add it to the list
        this.list_target.appendChild( new_row );

    };
};

function show_movable_div(hidden_id,process_name)
{
    if(trimAll(document.getElementById(hidden_id).value,"") != "")
    {
        show_div(hidden_id,'300','400',process_name)
    }
}

function show_calendar_set_ids(textbox_id)
{
    images = document.getElementsByTagName("img");

    for(var i=0;i<images.length;i++)
    {
        image_source = images[i].src;

        if(image_source.substr(image_source.length - 7,7) == "cal.gif")
        {
            images[i].id="image_calendar"+i;
        }
    }
    var e = window.event;
    image_id = e.target.id;

    var cal = new Mobius.Calendar.setup({
        inputField: textbox_id,
        step: 1,
        ifFormat: "%Y-%m-%d",
        button: image_id,
        showsTime: false
    });
    }

    function checkAll(checkname, exby)
{
    if(!checkname)
    return;

    if(typeof(checkname.length) == "undefined")
    {
        checkname.checked = exby.checked? true:false
    }
    else
    {
        for (i = 0; i < checkname.length; i++)
  	checkname[i].checked = exby.checked? true:false
    }
}

function checkDecimal(id,val,allowed,notallow,format,decval){
    
   if(val!=''){
   var num = parseFloat(val);
  if(!Number.isNaN(num) && num.toFixed(decval).toString() === val){
     return true;
  }
  else{
      
      alert('Plase enter valid decimal value');
	   document.getElementById(field_name).value='';
      setTimeout(function() { document.getElementById(id).focus(); }, 10);
      return false;
  }
   }
}