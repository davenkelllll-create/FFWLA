<?php
/**
 * Template Name: Veranstaltungskalender
 */
get_header();
?>

<div class="fw-page-header">
    <div class="container">
        <nav aria-label="Breadcrumb" class="fw-breadcrumb mb-1">
            <a href="<?= esc_url(home_url('/')) ?>">Startseite</a> / Veranstaltungskalender
        </nav>
        <h1><i class="bi bi-calendar3 me-2 text-fw-red"></i>Veranstaltungskalender</h1>
        <p class="text-muted mb-0">Alle öffentlichen Termine der FF</p>
    </div>
</div>

<section class="fw-section">
    <div class="container">
        <!-- Legende -->
        <div class="d-flex flex-wrap gap-3 mb-4">
            <span class="fw-badge" style="background:#ffe8e8;color:#990000;"><span style="background:#CC0000;width:10px;height:10px;border-radius:50%;display:inline-block;margin-right:5px;"></span>Übung</span>
            <span class="fw-badge" style="background:#e8f5ee;color:#1a7a3c;"><span style="background:#1a7a3c;width:10px;height:10px;border-radius:50%;display:inline-block;margin-right:5px;"></span>Veranstaltung</span>
            <span class="fw-badge" style="background:#fff3e0;color:#e07800;"><span style="background:#FF8C00;width:10px;height:10px;border-radius:50%;display:inline-block;margin-right:5px;"></span>Jugendfeuerwehr</span>
        </div>

        <div class="admin-card p-3">
            <div id="fw-calendar"></div>
        </div>

        <!-- Upcoming list -->
        <?php
        $upcoming = new WP_Query([
            'post_type'      => 'fw_termin',
            'posts_per_page' => 10,
            'post_status'    => 'publish',
            'meta_query'     => [
                ['key'=>'fw_termin_public','value'=>'1'],
                ['key'=>'fw_termin_start','value'=>current_time('Y-m-d\TH:i'),'compare'=>'>=','type'=>'DATETIME'],
            ],
            'meta_key'  => 'fw_termin_start',
            'orderby'   => 'meta_value',
            'order'     => 'ASC',
        ]);
        $months_de = ['Jan','Feb','Mär','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'];
        if ($upcoming->have_posts()): ?>
        <div class="mt-5">
            <h2 class="fw-section-title mb-4">Nächste Termine</h2>
            <div class="d-flex flex-column gap-3" style="max-width:680px;">
            <?php while ($upcoming->have_posts()): $upcoming->the_post();
                $start = get_post_meta(get_the_ID(),'fw_termin_start',true);
                $ts    = strtotime($start);
                $loc   = get_post_meta(get_the_ID(),'fw_termin_location',true);
                $color = get_post_meta(get_the_ID(),'fw_termin_color',true) ?: '#CC0000';
                $desc  = get_the_content();
            ?>
            <div class="fw-termin">
                <div class="fw-termin__date" style="background:<?= esc_attr($color) ?>">
                    <div class="fw-termin__day"><?= date('d',$ts) ?></div>
                    <div class="fw-termin__month"><?= $months_de[date('n',$ts)-1] ?></div>
                </div>
                <div class="fw-termin__info">
                    <div class="fw-termin__title"><?php the_title(); ?></div>
                    <div class="fw-termin__meta">
                        <span><i class="bi bi-clock me-1"></i><?= date('H:i',$ts) ?> Uhr</span>
                        <?php if ($loc): ?><span><i class="bi bi-geo-alt me-1"></i><?= esc_html($loc) ?></span><?php endif; ?>
                    </div>
                    <?php if ($desc): ?><p class="mb-0 mt-1 small text-muted"><?= esc_html(wp_strip_all_tags($desc)) ?></p><?php endif; ?>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Event Modal -->
<div class="modal fade" id="terminModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--fw-red);color:#fff;">
                <h5 class="modal-title" id="terminModalLabel">Termin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="terminModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Schließen</button>
            </div>
        </div>
    </div>
</div>

<?php get_footer();
