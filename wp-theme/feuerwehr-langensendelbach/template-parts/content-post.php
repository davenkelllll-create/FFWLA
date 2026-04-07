<?php
$terms = get_the_terms(get_the_ID(), 'fw_nachrichtentyp');
$slug  = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->slug : '';
$icons = ['einsatz'=>'fire','uebung'=>'gear-wide-connected','veranstaltung'=>'calendar-event','presse'=>'megaphone'];
$icon  = $icons[$slug] ?? 'newspaper';
?>
<div class="fw-card">
    <?php if (has_post_thumbnail()): ?>
    <?php the_post_thumbnail('fw-card', ['class'=>'fw-card__img']); ?>
    <?php else: ?>
    <div class="fw-card__img--placeholder"><i class="bi bi-<?= $icon ?>"></i></div>
    <?php endif; ?>
    <div class="fw-card__body">
        <div class="fw-card__meta">
            <?= fw_get_type_badge() ?>
            <span><i class="bi bi-calendar3 me-1"></i><?= get_the_date('d.m.Y') ?></span>
        </div>
        <h3 class="fw-card__title">
            <a href="<?= esc_url(get_permalink()) ?>"><?php the_title(); ?></a>
        </h3>
        <p class="fw-card__excerpt"><?php the_excerpt(); ?></p>
    </div>
    <div class="fw-card__footer d-flex align-items-center justify-content-between">
        <a href="<?= esc_url(get_permalink()) ?>" class="btn btn-link btn-sm text-danger p-0">
            Weiterlesen <i class="bi bi-arrow-right ms-1"></i>
        </a>
        <small class="text-muted"><i class="bi bi-person me-1"></i><?= get_the_author() ?></small>
    </div>
</div>
