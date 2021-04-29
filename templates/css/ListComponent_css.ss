<% if $ImageMargin %>
@media (min-width: 750px) {
  
  {$CSSID} div.items.image-align-left > article.has-image > div.image,
  {$CSSID} div.items.image-align-stagger > article.has-image:nth-child(odd) > div.image {
    max-width: {$ImageMargin}px;
  }
  
  {$CSSID} div.items.image-align-left > article.has-image > section.content,
  {$CSSID} div.items.image-align-stagger > article.has-image:nth-child(odd) > section.content {
    margin-left: {$ImageMargin}px;
  }
  
  {$CSSID} div.items.image-align-right > article.has-image > div.image,
  {$CSSID} div.items.image-align-stagger > article.has-image:nth-child(even) > div.image {
    max-width: {$ImageMargin}px;
  }
  
  {$CSSID} div.items.image-align-right > article.has-image > section.content,
  {$CSSID} div.items.image-align-stagger > article.has-image:nth-child(even) > section.content {
    margin-right: {$ImageMargin}px;
  }
  
}
<% end_if %>