{extends file="layout.tpl"}

{block name="title"}{$article->title}{/block}

{block name="content"}
    <article>
        <h1>{$article->title}</h1>

        <div class="meta">
            <span>Создана: {$article->created_at|date_format:"%d.%m.%Y %H:%M"}</span>
            &bull;
            <span>Опубликована: {$article->published_at|date_format:"%d.%m.%Y %H:%M"}</span>
            &bull;
            <span>Просмотров: {$article->views}</span>
        </div>

        {if $article->image}
            <img src="{$article->image|escape}" alt="{$article->title|escape}">
        {/if}

        {if $article->description}
            <p><em>{$article->description}</em></p>
        {/if}

        <div class="content">
            {$article->body nofilter}
        </div>

        <div class="categories">
            <b>Категории</b>:

            {foreach $article->categories as $category}
                <a href="/category/{$category->id}">{$category->title}</a>
            {/foreach}
        </div>
    </article>

    <section class="similar-articles">
        {if $similar}
            <h2>Похожие статьи</h2>
            <ul>
                {foreach $similar as $article}
                    <li>
                        <a href="/article/{$article->id}">{$article->title}</a>
                    </li>
                {/foreach}
            </ul>
        {else}
            <p>У статьи нет похожих статей.</p>
        {/if}
    </section>
{/block}