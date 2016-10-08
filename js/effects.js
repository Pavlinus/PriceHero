function playInfoWindow(msg)
{
    if($(".info_window").css("right") !== '-285px')
    {
        return;
    }
    
    $(".info_window span").text(msg);
    $(".info_window").animate({"right" : "0px"}, 300);
    $(".info_window").delay(3000).animate({"right" : "-285px"}, 300);
}