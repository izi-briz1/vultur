{extends file="layout.tpl"}

{block name="content"}
    {foreach $categories as $category}
        <section class="category-block">
            <h2><a href="/category/{$category->id}">{$category->title}</a></h2>
            <ul>
                {foreach $category->articles as $article}
                    <li>
                        <a href="/article/{$article->id}">{$article->title}</a>
                        <small>{$article->published_at|date_format:"%d.%m.%Y"}</small>
                    </li>
                {/foreach}
            </ul>

            <a href="/category/{$category->id}">Все статьи &hellip;</a>
        </section>
    {/foreach}
{/block}