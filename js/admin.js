jQuery( function() 
    {       
          var $params = jQuery;
          
          // ajaxs global callback 
          
          function ajax_actions ( actions, vals, sets )
          {
              
              var scripts = ajax_script.ajax_url;
              var values  = vals;
              var setups  = sets;  
    
              $params.ajax ( 
              {
                      data: 
                      { 
                         action : actions, 
                         value  : values,
                      },
                      type   : 'POST',
                      url    : scripts,
                      beforeSend : function() 
                      { 
                           $params( '.loaders' ).addClass( 'true' );
                      },
                      error : function( xhr, status, err ) 
                      {
                           // Handle errors
                      },
                      success : function( html, data ) 
                      {
                           console.log( html )  
                           $params( setups ).html( html );
                           $params( '.loaders' ).removeClass( 'true' );
                      }
              } 
              ) . done ( function( html, data ) 
                  {
                      $params( '.loaders' ).removeClass( 'true' );
                  }
              );       
          }
          
          // ajaxs media global browse 
          
          function media_uploads ( ems, imgs, inps, acts, res ) 
          {
              ems.preventDefault();
              
              var $image = wp.media( 
                { 
                        title: 'Upload Image',
                        multiple: false
                }
              ).open().on( 'select', function( ems ) 
                {
    
                    var $uploaded_image = $image.state().get( 'selection' ).first();
    
                    console.log( $uploaded_image );
                    var $image_url = $uploaded_image.toJSON().url;
    
                    $params( imgs ).attr( { 'src' : $image_url } );
                    $params( inps ).val( $image_url );
                    
                    ajax_actions( acts, $image_url, res );
                }
             );
          }
          
          // clear search filter
          
          $params( '.clear' ) . click( function () 
              {    
                   ajax_actions( 'ajaxs_clear', '', '#results-users__manager' );
                   console.log( 'clearing ...' ); 
    
              }
          );
          
          // search users manager filter
          
          $params( '.wp-forum__ajax_input' ) . keyup ( function ()
              {
                      var vals = $params(this).val();
                      ajax_actions ( 'ajaxs_search', vals, '#results-users__manager' );
              }
          );
          
          // box actions
          
          $params( '.users__comments' ).hover( function () 
              {
                  $params(this).find( '.users__comments-box' ).show();
              }, function () {
                  $params(this).find( '.users__comments-box' ).hide();
              }
          );
          
          $params( '.users__replys' ).hover( function () 
              {
                  $params(this).find( '.users__replys-box' ).show();
              }, function () {
                  $params(this).find( '.users__replys-box' ).hide();
              }
          );
          
          $params( '.users__likes' ).hover( function () 
              {
                  $params(this).find( '.users__likes-box' ).show();
              }, function () {
                  $params(this).find( '.users__likes-box' ).hide();
              }
          );
          
          $params( '.users__shares' ).hover( function () 
              {
                  $params(this).find( '.users__shares-box' ).show();
              }, function () {
                  $params(this).find( '.users__shares-box' ).hide();
              }
          );
          
          // checked settings user roles dashboard .
          
          $params( '.wp-forums__events-actions.added' ).click( function( ems )
              {
                   $params( 'input.checkbox-roles' ).each( function( i ) 
                       {
                           if ( ! $params( this ).is( ':checked' ) ) 
                           {    
                                console.log( 'add-attr' );
                                $params( this ).attr( 'checked', 'checked' );
                                $params( this ).prop( 'checked', true );
                           }   
                       }
                   );
              }
          );
          
          $params( '.wp-forums__events-actions.removed' ).click( function( ems )
              {
                   $params( 'input.checkbox-roles' ).each( function( i ) 
                       {
                           if ( $params( this ).is( ':checked' ) == true ) 
                           {    
                                console.log( 'remove-attr' );
                                $params( this ).removeAttr( 'checked' );
                                $params( this ).prop( 'checked', false );
                           } 
                       }
                   );
              }
          );
          
          // checkbox role ajax actions
          
          $params( '#publish.submit-checkbox' ).click( function( ems ) 
              {    
                   
                   var $roles = [];
                   
                   $params( 'input.checkbox-roles' ).each( function ( i ) 
                       {   
                           if( $params(this).is( ':checked' ) == true ) 
                           {
                               $roles[i] = $params( this ).val();
                           }
                       }
                   );
                   
                   console.log( $roles );
                
                   ajax_actions( 'ajaxs_settings_add', $roles, '#results-users__manager' );
              }
          ); 
          
          /** wp media **/
          
          $params( 'a.browse-profiles' ).click( function( ems ) 
          { 
                    ems.preventDefault();
                    
                    var $image = wp.media( 
                        { 
                                title: 'Upload Image',
                                multiple: false
                        }
                    ).open().on( 'select', function( ems ) 
                        {
    
                            var $uploaded_image = $image.state().get( 'selection' ).first();
                            var $ids = $params( 'input.profiles-id__hidden' ).val();
                             
                            console.log( $uploaded_image );
                            var $image_url = $uploaded_image.toJSON().url;

                            $params( '.profile-link img' ).attr( { 'src' : $image_url } );
                            $params( 'input.profiles-input__hidden' ).val( $image_url );
                            $params( '.item.profile-'+$ids ).find( 'img.photo' ).attr( { 'src' : $image_url } );
                            
                            ajax_actions( 'ajaxs_profiles_edit', $image_url, '' );
                        }
                    );
              }
          );
          
          /** insert posts draft **/
          
          $params( 'input#save-post_field' ).click( function( ems ) 
              {     
                    var $fields  = []; 
                    var $scripts = ajax_script.ajax_url;
                    
                    $params( '.field-posts-draft' ).each( function (i) 
                        {
                             $fields[i] = $params( this ).val();
                        }
                    );  
                    
                    console.log( $fields ); 
                    
                    $params.ajax ( 
                      {
                              data: 
                              { 
                                 action : 'ajaxs_posts_draft', 
                                 value  : $fields,
                              },
                              type   : 'POST',
                              url    : $scripts,
                              beforeSend : function() 
                              { 
                                   $params( '.loaders-posts-draft' ).addClass( 'true' );
                              },
                              error : function( xhr, status, err ) 
                              {
                                   // Handle errors
                              },
                              success : function( html, data ) 
                              {
                                   console.log( html )  
                                   $params( '.loaders-posts-draft' ).removeClass( 'true' );
                                   $params( '.post-manager' ).append( html );
                              }
                      } 
                      ) . done ( function( html, data ) 
                          {
                              $params( '.loaders-posts-draft' ).removeClass( 'true' );
                          }
                      );       
              }
          );
          
    }
);