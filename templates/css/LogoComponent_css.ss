{$CSSID} div.logo img {
<% if $LogoWidthNarrow %>  width: {$LogoWidthNarrow}px;<% end_if %>
<% if $LogoHeightNarrow %>  height: {$LogoHeightNarrow}px;<% end_if %>
}

{$CSSID} div.text .title {
  font-size: {$TitleFontSizeNarrowCSS};
  font-weight: {$TitleFontWeight};
<% if $TitleFontColorCSS %>  color: {$TitleFontColorCSS};<% end_if %>
<% if $TitleFontFamilyCSS %>  font-family: {$TitleFontFamilyCSS};<% end_if %>
}

{$CSSID} div.text .subtitle {
  font-size: {$SubtitleFontSizeNarrowCSS};
  font-weight: {$SubtitleFontWeight};
<% if $SubtitleFontColorCSS %>  color: {$SubtitleFontColorCSS};<% end_if %>
<% if $SubtitleFontFamilyCSS %>  font-family: {$SubtitleFontFamilyCSS};<% end_if %>
}

{$CSSID} div.logo {
<% if $LogoMarginTopCSS %>  margin-top: {$LogoMarginTopCSS};<% end_if %>
<% if $LogoMarginLeftCSS %>  margin-left: {$LogoMarginLeftCSS};<% end_if %>
<% if $LogoMarginRightCSS %>  margin-right: {$LogoMarginRightCSS};<% end_if %>
<% if $LogoMarginBottomCSS %>  margin-bottom: {$LogoMarginBottomCSS};<% end_if %>
}

{$CSSID} div.icon {
<% if $IconMarginTopCSS %>  margin-top: {$IconMarginTopCSS};<% end_if %>
<% if $IconMarginLeftCSS %>  margin-left: {$IconMarginLeftCSS};<% end_if %>
<% if $IconMarginRightCSS %>  margin-right: {$IconMarginRightCSS};<% end_if %>
<% if $IconMarginBottomCSS %>  margin-bottom: {$IconMarginBottomCSS};<% end_if %>
}

{$CSSID} div.text {
<% if $TextMarginTopCSS %>  margin-top: {$TextMarginTopCSS};<% end_if %>
<% if $TextMarginLeftCSS %>  margin-left: {$TextMarginLeftCSS};<% end_if %>
<% if $TextMarginRightCSS %>  margin-right: {$TextMarginRightCSS};<% end_if %>
<% if $TextMarginBottomCSS %>  margin-bottom: {$TextMarginBottomCSS};<% end_if %>
}

{$CSSID} div.icon i {
  font-size: {$IconSizeNarrowCSS};
  line-height: {$IconSizeNarrowCSS};
}

@media (min-width: 750px) {
  
  {$CSSID} div.logo img {
  <% if $LogoWidthWide %>  width: {$LogoWidthWide}px;<% end_if %>
  <% if $LogoHeightWide %>  height: {$LogoHeightWide}px;<% end_if %>
  }
  
  {$CSSID} div.text .title {
    font-size: {$TitleFontSizeCSS};
  }
  
  {$CSSID} div.text .subtitle {
    font-size: {$SubtitleFontSizeCSS};
  }
  
  {$CSSID} div.icon i {
    font-size: {$IconSizeCSS};
    line-height: {$IconSizeCSS};
  }
  
}

<% if $UseRetinaBitmap %>

@media only screen and (-webkit-min-device-pixel-ratio: 1.3),
only screen and (-o-min-device-pixel-ratio: 13/10),
only screen and (min-resolution: 120dpi) {
  
  {$CSSID} div.logo img.png.no-svg,
  .no-svgasimg {$CSSID} div.logo img.png {
    display: none;
  }
  
  {$CSSID} div.logo img.png-hd.no-svg,
  .no-svgasimg {$CSSID} div.logo img.png-hd {
    display: inline-block;
  }
  
}

<% end_if %>