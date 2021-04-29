body {
  <% if $SilverWareBodyColor %>color: #{$SilverWareBodyColor};<% end_if %>
  <% if $SilverWareBodyFont %><% with $SilverWareBodyFont %>font-family: {$StyleFontFamily};<% end_with %><% end_if %>
}

<% if $SilverWareLinkColor %>
.typography a {
  color: #{$SilverWareLinkColor};
}
<% end_if %>

<% if $SilverWareLinkHoverColor %>
.typography a:hover {
  color: #{$SilverWareLinkHoverColor};
}
<% end_if %>

<% if $SilverWareLinkActiveColor %>
.typography a:active {
  color: #{$SilverWareLinkActiveColor};
}
<% end_if %>

<% if $SilverWareLinkVisitedColor %>
.typography a:visited {
  color: #{$SilverWareLinkVisitedColor};
}
<% end_if %>

.typography h1,
.typography h2,
.typography h3,
.typography h4,
.typography h5,
.typography h6 {
  <% if $SilverWareHeadingColor %>color: #{$SilverWareHeadingColor};<% end_if %>
  <% if $SilverWareHeadingFont %><% with $SilverWareHeadingFont %>font-family: {$StyleFontFamily};<% end_with %><% end_if %>
}

.typography h1 {
  <% if $SilverWareHeading1Color %>color: #{$SilverWareHeading1Color};<% end_if %>
  <% if $SilverWareHeading1Weight %>font-weight: {$SilverWareHeading1Weight};<% end_if %>
  <% if $SilverWareHeading1NarrowCSS %>font-size: {$SilverWareHeading1NarrowCSS};<% end_if %>
  <% if $SilverWareHeading1LineHeight %>line-height: {$SilverWareHeading1LineHeight};<% end_if %>
}

.typography h2 {
  <% if $SilverWareHeading2Color %>color: #{$SilverWareHeading2Color};<% end_if %>
  <% if $SilverWareHeading2Weight %>font-weight: {$SilverWareHeading2Weight};<% end_if %>
  <% if $SilverWareHeading2NarrowCSS %>font-size: {$SilverWareHeading2NarrowCSS};<% end_if %>
  <% if $SilverWareHeading2LineHeight %>line-height: {$SilverWareHeading2LineHeight};<% end_if %>
}

.typography h3 {
  <% if $SilverWareHeading3Color %>color: #{$SilverWareHeading3Color};<% end_if %>
  <% if $SilverWareHeading3Weight %>font-weight: {$SilverWareHeading3Weight};<% end_if %>
  <% if $SilverWareHeading3NarrowCSS %>font-size: {$SilverWareHeading3NarrowCSS};<% end_if %>
  <% if $SilverWareHeading3LineHeight %>line-height: {$SilverWareHeading3LineHeight};<% end_if %>
}

.typography h4 {
  <% if $SilverWareHeading4Color %>color: #{$SilverWareHeading4Color};<% end_if %>
  <% if $SilverWareHeading4Weight %>font-weight: {$SilverWareHeading4Weight};<% end_if %>
  <% if $SilverWareHeading4NarrowCSS %>font-size: {$SilverWareHeading4NarrowCSS};<% end_if %>
  <% if $SilverWareHeading4LineHeight %>line-height: {$SilverWareHeading4LineHeight};<% end_if %>
}

.typography h5 {
  <% if $SilverWareHeading5Color %>color: #{$SilverWareHeading5Color};<% end_if %>
  <% if $SilverWareHeading5Weight %>font-weight: {$SilverWareHeading5Weight};<% end_if %>
  <% if $SilverWareHeading5NarrowCSS %>font-size: {$SilverWareHeading5NarrowCSS};<% end_if %>
  <% if $SilverWareHeading5LineHeight %>line-height: {$SilverWareHeading5LineHeight};<% end_if %>
}

.typography h6 {
  <% if $SilverWareHeading6Color %>color: #{$SilverWareHeading6Color};<% end_if %>
  <% if $SilverWareHeading6Weight %>font-weight: {$SilverWareHeading6Weight};<% end_if %>
  <% if $SilverWareHeading6NarrowCSS %>font-size: {$SilverWareHeading6NarrowCSS};<% end_if %>
  <% if $SilverWareHeading6LineHeight %>line-height: {$SilverWareHeading6LineHeight};<% end_if %>
}

@media (min-width: 750px) {
  
  .typography h1 {
    <% if $SilverWareHeading1WideCSS %>font-size: {$SilverWareHeading1WideCSS};<% end_if %>
  }
  
  .typography h2 {
    <% if $SilverWareHeading2WideCSS %>font-size: {$SilverWareHeading2WideCSS};<% end_if %>
  }
  
  .typography h3 {
    <% if $SilverWareHeading3WideCSS %>font-size: {$SilverWareHeading3WideCSS};<% end_if %>
  }
  
  .typography h4 {
    <% if $SilverWareHeading4WideCSS %>font-size: {$SilverWareHeading4WideCSS};<% end_if %>
  }
  
  .typography h5 {
    <% if $SilverWareHeading5WideCSS %>font-size: {$SilverWareHeading5WideCSS};<% end_if %>
  }
  
  .typography h6 {
    <% if $SilverWareHeading6WideCSS %>font-size: {$SilverWareHeading6WideCSS};<% end_if %>
  }
  
}

.typography pre {
  <% if $SilverWareMonospaceColor %>color: #{$SilverWareMonospaceColor};<% end_if %>
  <% if $SilverWareMonospaceFont %><% with $SilverWareMonospaceFont %>font-family: {$StyleFontFamily};<% end_with %><% end_if %>
}

a.btn,
a.button,
form button,
form .ss-ui-button,
form .Actions .action,
form input[type="submit"],
form input[type="button"] {
  <% if $SilverWareButtonColor %>color: #{$SilverWareButtonColor};<% end_if %>
  <% if $SilverWareButtonBackgroundColor %>background-color: #{$SilverWareButtonBackgroundColor};<% end_if %>
  <% if $SilverWareButtonFont %><% with $SilverWareButtonFont %>font-family: {$StyleFontFamily};<% end_with %><% end_if %>
  <% if $SilverWareButtonNarrowCSS %>font-size: {$SilverWareButtonNarrowCSS};<% end_if %>
}

a.btn:hover,
a.btn:focus,
a.button:hover,
a.button:focus,
form button:hover,
form button:focus,
form .ss-ui-button:hover,
form .ss-ui-button:focus,
form .Actions .action:hover,
form .Actions .action:focus,
form input[type="submit"]:hover,
form input[type="submit"]:focus,
form input[type="button"]:hover,
form input[type="button"]:focus {
  <% if $SilverWareButtonHoverColor %>color: #{$SilverWareButtonHoverColor};<% end_if %>
  <% if $SilverWareButtonHoverBackgroundColor %>background-color: #{$SilverWareButtonHoverBackgroundColor};<% end_if %>
}

a.btn:active,
a.button:active,
form button:active,
form .ss-ui-button:active,
form .Actions .action:active,
form input[type="submit"]:active,
form input[type="button"]:active {
  <% if $SilverWareButtonActiveColor %>color: #{$SilverWareButtonActiveColor};<% end_if %>
  <% if $SilverWareButtonActiveBackgroundColor %>background-color: #{$SilverWareButtonActiveBackgroundColor};<% end_if %>
}

a.btn.secondary,
a.button.secondary,
form button.secondary,
form input[type="reset"] {
  <% if $SilverWareButton2Color %>color: #{$SilverWareButton2Color};<% end_if %>
  <% if $SilverWareButton2BackgroundColor %>background-color: #{$SilverWareButton2BackgroundColor};<% end_if %>
}

a.btn.secondary:hover,
a.btn.secondary:focus,
a.button.secondary:hover,
a.button.secondary:focus,
form button.secondary:hover,
form button.secondary:focus,
form input[type="reset"]:hover,
form input[type="reset"]:focus {
  <% if $SilverWareButton2HoverColor %>color: #{$SilverWareButton2HoverColor};<% end_if %>
  <% if $SilverWareButton2HoverBackgroundColor %>background-color: #{$SilverWareButton2HoverBackgroundColor};<% end_if %>
}

a.btn.secondary:active,
a.button.secondary:active,
form button.secondary:active,
form input[type="reset"]:active {
  <% if $SilverWareButton2ActiveColor %>color: #{$SilverWareButton2ActiveColor};<% end_if %>
  <% if $SilverWareButton2ActiveBackgroundColor %>background-color: #{$SilverWareButton2ActiveBackgroundColor};<% end_if %>
}

@media (min-width: 750px) {
  
  a.btn,
  a.button,
  form button,
  form .ss-ui-button,
  form .Actions .action,
  form input[type="submit"],
  form input[type="button"] {
    <% if $SilverWareButtonWideCSS %>font-size: {$SilverWareButtonWideCSS};<% end_if %>
  }
  
}
