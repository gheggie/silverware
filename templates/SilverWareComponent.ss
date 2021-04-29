<!DOCTYPE html>

<html class="no-js" lang="$ContentLocale">
  <head>
    <% base_tag %>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    $MetaTags(false)<title>$Title</title>
  </head>
  <body class="cms-preview">
    <div class="cms-preview-wrapper">
      <header>
        <h1>
          <i class="fa fa-fw fa-eye"></i>
          <span class="title"><% _t('SilverWareComponent_ss.PREVIEW', 'Preview') %></span>
          <span class="class">($ComponentType)</span>
        </h1>
      </header>
      <div class="component">
        $RenderPreview
      </div>
    </div>
  </body>
</html>