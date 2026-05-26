{extends file="layout.tpl"}

{block name="title"}{$category->title|escape} — Блог{/block}

{block name="content"}
    <article class="category-page">
        <header class="category-header">
            <h1>{$category->title|escape}</h1>
            {if $category->description}
                <p class="category-description">{$category->description|escape}</p>
            {/if}
        </header>

        <h2>Список статей:</h2>

        <div class="category-toolbar">
            <span class="category-toolbar__label">Сортировать:</span>

            {* По дате *}
            {if $sort === 'published_at'}
                {assign var="dateOrder" value=($order === 'desc' ? 'asc' : 'desc')}
            {else}
                {assign var="dateOrder" value='desc'}
            {/if}

            <a href="?sort=published_at&order={$dateOrder}" class="{if $sort === 'published_at'}sort-link--active{/if}">
                По дате

                {if $sort === 'published_at'}
                    {if $order === 'desc'}↓{else}↑{/if}
                {/if}
            </a>

            {* По просмотрам *}
            {if $sort === 'views'}
                {assign var="viewsOrder" value=($order === 'desc' ? 'asc' : 'desc')}
            {else}
                {assign var="viewsOrder" value='desc'}
            {/if}

            <a href="?sort=views&order={$viewsOrder}" class="{if $sort === 'views'}sort-link--active{/if}">
                По просмотрам
                {if $sort === 'views'}
                    {if $order === 'desc'}↓{else}↑{/if}
                {/if}
            </a>
        </div>

        {if $articles}
            <ul class="article-list">
                {foreach $articles as $article}
                    <li class="article-card">
                        {if $article->image}
                            <a href="/article/{$article->id}" class="article-card__image">
                                <img src="{$article->image|escape}" alt="{$article->title|escape}">
                            </a>
                        {/if}
                        <div class="article-card__body">
                            <h2 class="article-card__title">
                                <a href="/article/{$article->id}">{$article->title|escape}</a>
                            </h2>
                            <p class="article-card__description">
                                {$article->description|escape}
                            </p>
                            <div class="article-card__meta">
                                <time datetime="{$article->published_at}">
                                    {$article->published_at|date_format:"%d.%m.%Y"}
                                </time>
                                <span class="article-card__views">
                                    Просмотров: {$article->views}
                                </span>
                            </div>
                        </div>
                    </li>
                {/foreach}
            </ul>

            {if $pages > 1}
                <nav class="pagination" aria-label="Постраничная навигация">
                    {if $page > 1}
                        <a href="?sort={$sort|escape:'url'}&order={$order}&page={$page - 1}" class="pagination__link">
                            ← Назад
                        </a>
                    {/if}

                    {foreach $pages as $p}
                        {if $p === $page}
                            <span class="pagination__link pagination__link--active">{$p}</span>
                        {elseif $p === '...'}
                            <span class="pagination__ellipsis">…</span>
                        {else}
                            <a href="?sort={$sort|escape:'url'}&order={$order}&page={$p}" class="pagination__link">{$p}</a>
                        {/if}
                    {/foreach}

                    {if $page < $pages}
                        <a href="?sort={$sort|escape:'url'}&order={$order}&page={$page + 1}" class="pagination__link">
                            Вперёд →
                        </a>
                    {/if}
                </nav>
            {/if}
        {else}
            <p class="empty-state">В этой категории пока нет статей.</p>
        {/if}
    </article>
{/block}