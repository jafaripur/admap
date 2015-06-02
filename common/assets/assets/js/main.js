$(document).ready(function () {
    if ($.pjax){
        $.pjax.defaults.timeout = 20000;
    }
    $.ytLoad();
    $(".fancybox").fancybox({
        beforeLoad: function () {
            var link = $(this).attr("href");
            if (link == window.location.pathname || link == window.location) {
                return false;
            }
        }
    });
});
/*
if ($.pjax)
{
    $(document).on('pjax:send', function() {
        showLoading('body', true);
    });
    $(document).on('pjax:complete', function() {
        hideLoading('body', true);
    });
}
*/
function reloadPjax(container)
{
    if ($.pjax)
    {
        $.pjax.reload({container:'#'+container});
    }
}
function ajaxLoadHtml(formName, container) {
    var form = $("#" + formName);
    showLoading(container, false);
    return $.post(form.attr("action"), form.serialize(), function (data) {
        $("#" + container).html(data);
    }).always(function () {
        hideLoading(container, false);
    });
}
function showLoading(container, isBody) {
    if (!isBody) {
        container = "#" + container;
    }
    $(container).waitMe({effect: "bounce", text: "", color: "#000", sizeW: "", sizeH: ""})
}
function hideLoading(container, isBody) {
    if (!isBody) {
        container = "#" + container;
    }
    $(container).waitMe("hide");
}
function deleteGridButton(url, confirmMessage, pjaxGridName, messageContainer){
    if(!confirm(confirmMessage))
    {
        return false;
    }
    if (messageContainer !== ''){
        $('#' + messageContainer).removeClass().html('');
    }
    $.post(url, null, function (data) {
        if (!data.error) {
            if (pjaxGridName !== ''){
                reloadPjax(pjaxGridName);
            }
            if (messageContainer !== ''){
                $('#' + messageContainer).addClass('alert alert-success').html(data.message);
            }
        }
        else {
            if (messageContainer !== ''){
                $('#' + messageContainer).addClass('alert alert-danger').html(data.message);
            }
        }
    }, 'json');
    return false;
}
function disableGridButton(url, pjaxGridName, messageContainer){
    if (messageContainer !== ''){
        $('#' + messageContainer).removeClass().html('');
    }
    $.post(url, null, function (data) {
        if (!data.error) {
            if (pjaxGridName !== ''){
                reloadPjax(pjaxGridName);
            }
            if (messageContainer !== ''){
                $('#' + messageContainer).addClass('alert alert-success').html(data.message);
            }
        }
        else {
            if (messageContainer !== ''){
                $('#' + messageContainer).addClass('alert alert-danger').html(data.message);
            }
        }
    }, 'json');
    return false;
}

function loadAdvers(pushState, geolocating, formName, adverObj){
    adverObj.disableZoom = true;
    if (!geolocating){
        loadAdversMaster(pushState, formName, adverObj);
        return false;
    }
    var fullAddress = '';
    var country = $('#search-country_id option:selected').text();
    var province = $('#search-province_id option:selected').text();
    var city = $('#search-city_id option:selected').text();
    var address = $('#search-address').val();
    var useGeolocating = false;;
    if (country !== '')
        fullAddress += country + ', ';
    if (province !== ''){
        fullAddress += province + ', ';
        useGeolocating = true;
    }
    if (city !== '')
    {
        fullAddress += city + ', ';
        useGeolocating = true;
    }
    if (!useGeolocating){
        loadAdversMaster(pushState, formName, adverObj);
        return false;
    }
    if (address !== '')
        fullAddress += address;
    var geocoder = new google.maps.Geocoder();
    if (geocoder && fullAddress !== ''){
        geocoder.geocode({'address': fullAddress}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK)
            {
                adverObj.map.setCenter(results[0].geometry.location, 14);
                adverObj.map.fitBounds(results[0].geometry.viewport);
                loadAdversMaster(pushState, formName, adverObj);
            }
            else{
                loadAdversMaster(pushState, formName, adverObj);
            }
        });
    }
    else{
        loadAdversMaster(pushState, formName, adverObj);
    }
}
function loadAdversMaster(pushState, formName, adverObj){
    var zoom = adverObj.map.getZoom();
    var bounds = adverObj.map.getBounds();
    var ne = bounds.getNorthEast();
    var sw = bounds.getSouthWest();
    var lat_max = ne.lat();
    var lat_min = sw.lat();
    var lng_min = sw.lng();
    var lng_max = ne.lng();
    var center = adverObj.map.getCenter();
    var latitude = center.lat();
    var longitude = center.lng();
    var searchData = $(formName).serialize();
    searchData = searchData.replace(/[^&]+=\.?(?:&|$)/g, '');
    searchData += '&lat_max='+lat_max+'&lat_min='+lat_min+'&lng_min='+lng_min+'&lng_max='+lng_max;
    searchData += '&latitude='+latitude+'&longitude='+longitude+'&zoom='+zoom;
    if (pushState){
        var state = "?" + searchData;
        window.history.replaceState({}, null, state);
    }
    if(zoom >= 17)
    {
        getMarker(searchData, adverObj);
        return false;
    }
    $('#map_loading_img').show(300);
    if (adverObj.markerAjaxObj)
        adverObj.markerAjaxObj.abort();
    deleteCurrentMarker(adverObj);
    adverObj.markerAjaxObj = $.get(adverObj.clusterSearchUrl, searchData, function(data){
        var markerCount = data.length;
        for (var i = 0; i < markerCount; i++)
        {
            var clusteringClass = getClusteringMarkerClass(data[i].adver_count);
            var marker = new RichMarker({
                position: new google.maps.LatLng(data[i].lat, data[i].lng),
                map: adverObj.map,
                content: "<div id=\"cluster" + i + "\" class=\"bubble_container " + clusteringClass[0] + "\" onclick=\"mapZoomClusteringClick(adverObj, " + data[i].lat + ", " + data[i].lng + ", 2, "+ data[i].adver_count +");\"><strong>" + data[i].adver_count + "</strong></div>",
                draggable: false,
                shadow: false
            });
            adverObj.markersArray.push(marker);
        }
        adverObj.disableZoom = false;
    }, 'JSON').always(function(){
        $('#map_loading_img').hide(300);
        adverObj.disableZoom = false;
    });
}
function getClusteringMarkerClass(countOfMarkers)
{

    if (countOfMarkers < 50)
        return ['bubble1', 53, 52];
    else if (countOfMarkers < 200)
        return ['bubble2', 56, 55];
    else if (countOfMarkers < 1000)
        return ['bubble3', 66, 65];
    else if (countOfMarkers < 10000)
        return ['bubble4', 78, 77];
    else if (countOfMarkers >= 10000)
        return ['bubble5', 90, 89];
}
function mapZoomClusteringClick(adverObj, lat, lng, zoomInc, count)
{
    var newZoom;
    if (count == 1){
        newZoom = 17;
    }
    else{
        newZoom = adverObj.map.getZoom() + zoomInc;   
    }
    adverObj.map.setZoom(newZoom);
    adverObj.map.setCenter(new google.maps.LatLng(lat, lng));
}
function deleteCurrentMarker(adverObj)
{
    if (adverObj.markersArray)
    {
        for (i = 0; i < adverObj.markersArray.length; i++){
            adverObj.markersArray[i].setMap(null);
        }
        adverObj.markersArray.length = 0;
    }
}
function getMarker(searchData, adverObj){
    $('#map_loading_img').show(300);
    if (adverObj.markerAjaxObj)
        adverObj.markerAjaxObj.abort();
    deleteCurrentMarker(adverObj);
    adverObj.markerAjaxObj = $.get(adverObj.markerSearchUrl, searchData, function(data){
        var markerCount = data.length;
        for (var i = 0; i < markerCount; i++)
        {
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(data[i].lat, data[i].lng),
                map: adverObj.map,
                ids: data[i].ids,
                icon: adverObj.markerImageUrl,
                title: data[i].title
            });
            google.maps.event.addListener(marker, "click", function(event)
            {
                var currentMarker = this;
                adverObj.infoWindow.close();
                if(this.html)
                {
                    adverObj.infoWindow.setContent(this.html);
                    adverObj.infoWindow.open(adverObj.map, currentMarker);
                }
                else
                {
                    $('#map_loading_img').show(300);
                    if (adverObj.infoWindowAjaxObj)
                        adverObj.infoWindowAjaxObj.abort();
                    adverObj.infoWindowAjaxObj = $.get(adverObj.infoWindowUrl, {ids: this.ids}, function(data){
                        currentMarker.html = generateHtmlContent(data);
                        adverObj.infoWindow.setContent(currentMarker.html);
                        adverObj.infoWindow.open(adverObj.map, currentMarker);
                    }, 'JSON').always(function(){
                        $('#map_loading_img').hide(300);
                    });
                }
            });
            adverObj.markersArray.push(marker);
        }
    }, 'JSON').always(function(){
        $('#map_loading_img').hide(300);
    });
}
function generateHtmlContent(data){
    var html = '<div class="adver_infowindow">';
    for(var i = 0; i < data.length; i++){
        if (i !== 0){
            html += '<hr>';
        }
        html += '<p class="infowindow_gallery">';
        var galleryCount = data[i].gallery.length;
        var imageIndex;
        for(var j = 0; j < galleryCount; j++){
            imageIndex = j + 1;
            var galleryStyle = (galleryCount !== 1 ? 'cursor: pointer;' : '')+(imageIndex !== 1 ? 'display: none;' : '');
            var imageScript = (galleryCount !== 1 ? "jQuery('#adversImage"+data[i].gallery[j].adver_id.toString()+imageIndex.toString()+"').hide();" : '');
            imageScript += (galleryCount === imageIndex ? "jQuery('#adversImage"+data[i].gallery[j].adver_id.toString()+"1').show();" : 
                    "jQuery('#adversImage"+data[i].gallery[j].adver_id.toString()+(parseInt(imageIndex)+1).toString()+"').show();");
            html += '<img class="img-thumbnail img-responsive" id="adversImage' + data[i].gallery[j].adver_id.toString() + imageIndex.toString() + '" onclick="'+imageScript+'" alt="'+data[i].gallery[j].title+'" style="'+galleryStyle+'" src="'+data[i].gallery[j].url+'"/>';
            if (imageIndex === galleryCount){
                html += '<br/>';
            }
        }
        html += '</p><p class="infowindow_class">';
        html += '<a href="'+data[i].url+'" target="_blank">'+data[i].title+'</a><br/>';
        html += data[i].category + '<br/>';
        html += data[i].address + '<br/>';
        html += data[i].full_address + '<br/>';
        html += '</p>';
    }
    html += '</div>';
    return html;
}
function changeAdverView(mode){
    if ($('#hiddenViewMode').val() === mode){
        return false;
    }
    $('#hiddenViewMode').val(mode);
    if (mode === 'map'){
        $('#gridView').hide();
        $('#mapView').show(200);
        setTimeout(function(){
            initializeAdverMap();
            //google.maps.event.trigger(adverObj.map, "resize");
        }, 500);
    }
    else if (mode === 'grid'){
        $('#mapView').hide();
        $('#gridView').show(200);
        reloadPjax('adverList-pjax');
    }
}