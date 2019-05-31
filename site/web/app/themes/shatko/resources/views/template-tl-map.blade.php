{{--
  Template Name: TL Map Template
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    @include('partials.page-header')
    @include('partials.content-page')

    @php
    function cleanme($string) {
     $string = str_replace(' ', '', $string);
     $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
     return preg_replace('/-+/', '', $string);
    }
    @endphp

    <div class="gm-locations-wrapper">
      <div class="contact-form-wrapper"></div>
      <div id="map"></div>
      <div class="zoom-out">-</div>
    </div>


    <script>


    function initMap() {
      var map = new google.maps.Map(document.getElementById('map'), {
        // zoom: 5,
        zoom: 2,
        zoomControl: false,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        fullscreenControl: false,
        draggable: false,
        center: {
          lat: 50,
          lng: 0
        },
        scrollwheel: false
      });

      styles = [
        {
            "featureType": "all",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "color": "#ffffff"
                }
            ]
        },
        {
            "featureType": "all",
            "elementType": "labels",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "administrative",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "administrative.country",
            "elementType": "geometry.stroke",
            "stylers": [
                {
                    "color": "#ffffff"
                },
                {
                    "weight": "0.75"
                },
                {
                    "visibility": "on"
                }
            ]
        },
        {
            "featureType": "administrative.country",
            "elementType": "labels",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "administrative.province",
            "elementType": "labels",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "administrative.locality",
            "elementType": "labels",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "administrative.neighborhood",
            "elementType": "labels",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "administrative.land_parcel",
            "elementType": "labels",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "landscape",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "on"
                }
            ]
        },
        {
            "featureType": "landscape",
            "elementType": "geometry.fill",
            "stylers": [
                {
                    "color": "#dddddd"
                }
            ]
        },
        {
            "featureType": "landscape",
            "elementType": "labels",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "transit",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "labels",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        }
      ];

      map.setOptions({
        styles: styles,
        minZoom: 2,
        maxZoom: 6
        });

      var markers = features.map(function(feature) {
        var singlePin = new google.maps.Marker({
          position: feature,
          label: feature.label,
          icon: feature.pin,
        });

        singlePin.addListener('click', function() {
           infowindow.open(map, singlePin);
           console.log('pin');
        });

        var infowindow = new google.maps.InfoWindow({
            content: feature.infocontent,
            pixelOffset: new google.maps.Size(0, 0),
        });



        // google.maps.event.addListener(singlePin, 'click', function() {
        //   infowindow.open(map,singlePin);
        // });

        infowindow.open(map,singlePin);
        return singlePin;

      });


      var markerCluster = new MarkerClusterer(
        map,
        markers,
        {imagePath: '@php echo App\asset_path('images/tl-map-pin.png'); @endphp'}
      );

      markerCluster.setMaxZoom(2);
      markerCluster.setGridSize(200);
      markerCluster.setStyles([
        {
          textColor: 'white',
          url: 'http://shatko.test/app/themes/shatko/dist/images/tl-map-cluster.png',
          height: 100,
          width: 100
        },
       {
          textColor: 'white',
          url: 'http://shatko.test/app/themes/shatko/dist/images/tl-map-cluster.png',
          height: 100,
          width: 100
        },
       {
          textColor: 'white',
          url: 'http://shatko.test/app/themes/shatko/dist/images/tl-map-cluster.png',
          height: 100,
          width: 100
        }
      ]);
      markerCluster.redraw();



      map.addListener('tilesloaded', function () {

        // jQuery('.pin-info-container').on( 'click', function(e) {
        //   console.log(jQuery(this).text());
        //   jQuery(this).toggleClass('pro-class');
        //   map.setZoom(2);
        //   var center = new google.maps.LatLng(50, 0);
        //   map.panTo(center);
        // });

      });





      map.addListener('idle', function() {



        jQuery('.zoom-out').one( 'click', function() {
          // console.log(jQuery(this).text());
          // jQuery(this).toggleClass('pro-class');
          map.setZoom(2);
          var center = new google.maps.LatLng(50, 0);
          map.panTo(center);
        });


        setTimeout(function(){
          console.log('Tiles loaded: ' + map.getZoom());
          var firstCluster = jQuery('.gm-style').children('div:first').children('div:nth-child(3)').children().children('div:nth-child(3)').children('div:nth-child(1)');
          var firstNumberOfLocations = firstCluster.text();
          var secondCluster = jQuery('.gm-style').children('div:first').children('div:nth-child(3)').children().children('div:nth-child(3)').children('div:nth-child(2)');
          var secondNumberOfLocations = secondCluster.text();
          var thirdCluster = jQuery('.gm-style').children('div:first').children('div:nth-child(3)').children().children('div:nth-child(3)').children('div:nth-child(3)');
          var thirdNumberOfLocations = thirdCluster.text();

          if (map.getZoom() == 2) {
            firstCluster.html("<div class='cluster-content'><p class='cluster-title'>@php _e( 'Europa', 'ThomasLloyd'); @endphp</p><p class='cluster-text'>" + firstNumberOfLocations + " @php _e( 'Standorte', 'ThomasLloyd'); @endphp</p></div>");
            secondCluster.html("<div class='cluster-content'><p class='cluster-title'>@php _e( 'Asien', 'ThomasLloyd'); @endphp</p><p class='cluster-text'>" + secondNumberOfLocations + " @php _e( 'Standorte', 'ThomasLloyd'); @endphp</p></div>");
            thirdCluster.html("<div class='cluster-content'><p class='cluster-title'>@php _e( 'Amerika', 'ThomasLloyd'); @endphp</p><p class='cluster-text'>" + thirdNumberOfLocations + " @php _e( 'Standorte', 'ThomasLloyd'); @endphp</p></div>");
            jQuery('.zoom-out').hide();
          } else {
            firstCluster.html( firstNumberOfLocations );
            secondCluster.html( secondNumberOfLocations );
            thirdCluster.html( thirdNumberOfLocations );
            jQuery('.zoom-out').show();
          }
        }, 200);
      });



    }

    var features = [
      @php
      if( have_rows('google_maps_pins') ):
        while ( have_rows('google_maps_pins') ) : the_row();
        @endphp
        {
          lat: @php echo get_sub_field('latitude'); @endphp,
          lng: @php echo get_sub_field('longitude'); @endphp,
          label: '',
          pin: '@php echo App\asset_path('images/tl-map-pin.png'); @endphp',
          title: '',
          // infocontent: '<div class="pin-info-container"><p id="@php echo cleanme(get_sub_field('link_text')); @endphp">@php echo get_sub_field('link_text'); @endphp</p></div>'
          infocontent: '<div id="pin-info-container-id" class="pin-info-container"><button onclick="tlLocations()">@php echo get_sub_field('link_text'); @endphp</button></div>'
        },
        @php
        endwhile;
      endif;
      @endphp
    ]




    function tlLocations() {
      jQuery('.contact-form-wrapper').toggleClass('test');
    }

    </script>




    <style media="screen">

      #map {
        height: 600px;
        width: 100%;
      }

      .gm-locations-wrapper {
        position: relative;
      }

      .gm-style-iw-d {
        overflow: visible !important;
      }

      .pin-info-container button {
        display: inline-block;
        color: #fff;
        text-transform: uppercase;
        background-color: #3D4958;
        border: none;
        outline: 0;
      }

      .pin-info-container {
        display: inline-block;
        color: #fff;
        text-transform: uppercase;
      }

      .pin-info-container > p {
        margin-bottom: 0;
      }

      button[title="Close"] {
        display: none !important;
      }

      .gm-style .gm-style-iw-c {
        border-radius: 0 !important;
        padding: 5px !important;
        text-align: center !important;
        background: #3D4958 !important;
      }

      .gm-style .gm-style-iw-t::after {
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 5px 5px 0 5px;
        border-color: #3d4958 transparent transparent transparent;
        line-height: 0px;
        transform: translate(-50%,-50%) rotate(0deg);
        top: 2px;
        background: none;
      }

      /* Pins */
      .pin-info-container {
        cursor: pointer;
      }

      /* Cluster */
      .cluster-content {
        display: inline-block;
        width: 100%;
      }

      .cluster-title {
        display: inline-block;
        width: 100%;
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translate(-50%, 0);
        text-transform: uppercase;
        font-size: 16px;
      }

      .cluster-text {
        display: inline-block;
        width: 100%;
        position: absolute;
        top: 5px;
        left: 50%;
        transform: translate(-50%, 0);
        text-transform: uppercase;
      }

      .contact-form-wrapper {
        width: 200px;
        height: 100vh;
        position: absolute;
        top: 0;
        right: -225px;
        background: #3D4958;
      }

      .zoom-out {
        background: red;
        position: absolute;
        bottom: 24px;
        right: 10px;
        display: inline-block;
        width: 40px;
        height: 40px;
        text-align: center;
        font-size: 40px;
        line-height: 35px;
        color: #fff;
        background: #3D4958;
        cursor: pointer;
      }

    </style>

    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQzYb980i8Vzz0Lm9gnMqHdYQyTf50ZAk&callback=initMap" async defer></script>

  @endwhile
@endsection
