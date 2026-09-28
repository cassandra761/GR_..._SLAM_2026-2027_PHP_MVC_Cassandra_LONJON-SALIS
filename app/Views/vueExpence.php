<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Note de Frais GSB</title>
    <link rel="stylesheet" href="<?php echo base_url('/public/css/style.css'); ?>" />
    <link rel="icon" href="logo GSB.png">
</head>

<body>
    <div class="header-connected">
        <div style="text-align: center; flex: 1;">
            <img src="logo.png" alt="Logo GSB" style="height: 40px;">
        </div>
        <div class="user-info">
            <div class="profile-badge">
                <div class="profile-icon">👤</div>
                <div class="profile-text">
                    <h3 id="userFullName"><?php echo htmlspecialchars($_SESSION['user1']['prenom'] ?? 'Utilisateur') . ' ' . htmlspecialchars($_SESSION['user1']['nom'] ?? ''); ?></h3>
                    <p id="userProfile"><?php echo htmlspecialchars($_SESSION['user1']['role'] ?? 'non défini'); ?></p>
                </div>
            </div>
            <a href="getdata?action=logout" class="logout-btn">Se déconnecter</a>

        </div>
    </div>

    <div class="container">
        <h1 style="color: #8ab98b; margin-bottom: 30px;">Espace de Gestion des Notes de Frais</h1>
        <?php if (!empty($message)): ?>
            <div style="padding:10px; background:#e8ffe8; border:1px solid #8ab98b; color:#185b1f; margin-bottom:12px;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="tabs">
            <button class="<?php echo $activeTab === 'new-expense' ? 'tab-button active' : 'tab-button'; ?>" onclick="switchTab('new-expense')">Nouvelle note</button>
            <button class="<?php echo $activeTab === 'my-expenses' ? 'tab-button active' : 'tab-button'; ?>" onclick="switchTab('my-expenses')">Mes notes de frais</button>
        </div>

        <!-- TAB 1: Nouvelle note -->
        <div id="new-expense" class="tab-content <?php echo $activeTab === 'new-expense' ? 'active' : ''; ?>">
            <h2>Renseigner fiche de frais</h2>

            <div style="margin-bottom:16px; display:flex; gap:12px; align-items:center;">
                <label for="ficheMonth" style="font-weight:600;">Mois :</label>
                <input type="month" id="ficheMonth" name="mois">
            </div>

            <form id="saveForfaitsForm" method="POST" style="display:none;">
                <input type="hidden" name="action" value="save_forfaits">
                <input type="hidden" name="mois" id="saveForfaitsMois">
                <input type="hidden" name="forfait_etape" id="hidden_forfait_etape">
                <input type="hidden" name="forfait_km" id="hidden_forfait_km">
                <input type="hidden" name="forfait_nuitees" id="hidden_forfait_nuitees">
                <input type="hidden" name="forfait_repas" id="hidden_forfait_repas">
                <input type="hidden" name="nbJustificatif" id="hidden_nbJustificatif" value="0">
            </form>

            <section style="margin-bottom:20px;">
                <h3 style="margin:0 0 10px 0; color:#7b90a6;">Éléments forfaitisés</h3>

                <form action="postdata" method="POST">
                <div class="expense-form">
                    <div class="form-group">
                        <label for="forfait_etape">Forfait Étape (quantité)</label>
                        <input name='ETP' type="number" id="forfait_etape" min="0" value=<?php echo $ETP[0]->quantite ?> required>
                    </div>
                    <div class="form-group">
                        <label for="forfait_km">Frais kilométriques (km)</label>
                        <input name='KM' type="number" id="forfait_km" min="0" value=<?php echo $KM[0]->quantite ?> required>
                    </div>
                    <div class="form-group">
                        <label for="forfait_nuitees">Nuitées hôtel (nb)</label>
                        <input name='NUI' type="number" id="forfait_nuitees" min="0" value=<?php echo $NUI[0]->quantite ?> required>
                    </div>
                    <div class="form-group">
                        <label for="forfait_repas">Repas restaurant (nb)</label>
                        <input name='REP' type="number" id="forfait_repas" min="0" value=<?php echo $REP[0]->quantite ?> required>
                    </div>
                    <input class="submit-btn" type="submit" value="Enregistrer forfaits">
                </div>
                </form>
                <!-- <div style="margin-top:12px;">
                    <button class="submit-btn" onclick="saveForfaits()">Enregistrer forfaits</button>
                </div> -->
            </section>

            <section>
                <h3 style="margin:0 0 10px 0; color:#7b90a6;">Frais hors forfait</h3>

                <form action="postdata" method="POST">
                <!-- <form id="hfForm" method="POST" onsubmit="return addHorsForfait(event)"> -->
                    <input type="hidden" name="action" value="add_hors_forfait">
                    <input type="hidden" name="mois" id="hfMois">

                    <div class="expense-form">
                        <div class="form-group">
                            <label for="hfDate">Date d'engagement</label>
                            <input type="date" id="hfDate" name="hfDate" required>
                        </div>
                        <div class="form-group">
                            <label for="hfLabel">Libellé</label>
                            <input type="text" id="hfLabel" name="hfLabel" required>
                        </div>
                        <div class="form-group">
                            <label for="hfAmount">Montant (€)</label>
                            <input type="number" id="hfAmount" name="hfAmount" step="0.01" required>
                        </div>
                        <input class="submit-btn" type="submit" value="Ajouter hors forfait">
                        <!-- <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="submit-btn">Ajouter hors forfait</button>
                        </div> -->
                    </div>
                </form>
            </section>
        </div>

        <!-- TAB 2: Mes notes de frais -->
        <div id="my-expenses" class="tab-content <?php echo $activeTab === 'my-expenses' ? 'active' : ''; ?>">
            <h2>Mes notes de frais</h2>
            <?php if (!empty($mesFiches)): ?>
                <table class="expenses-table">
                    <thead>
                        <tr>
                            <th>Mois</th>
                            <th>Justificatifs</th>
                            <th>Montant validé</th>
                            <th>Date modif</th>
                            <th>État</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mesFiches as $fiche): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fiche['mois']); ?></td>
                                <td><?php echo htmlspecialchars($fiche['nbJustificatif']); ?></td>
                                <td><?php echo number_format($fiche['montantValide'], 2, ',', ' '); ?> €</td>
                                <td><?php echo htmlspecialchars($fiche['dateModif']); ?></td>
                                <td><?php
                                    switch ($fiche['idEtat']) {
                                        case 'CR': echo 'En attente'; break;
                                        case 'VA': echo 'Validée'; break;
                                        case 'RB': echo 'Refusée / Remboursée'; break;
                                        case 'CL': echo 'Clôturée'; break;
                                        default: echo htmlspecialchars($fiche['idEtat']);
                                    }
                                ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
            <h2>Mes notes de hors frais</h2>
                <table class="expenses-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Libellé</th>
                            <th>Montant</th>
                            <th>ActÉtation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($horsForfaits)): ?>
                            <?php foreach ($horsForfaits as $hf): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($hf['date']); ?></td>
                                    <td><?php echo htmlspecialchars($hf['libelle']); ?></td>
                                    <td><?php echo number_format($hf['montant'], 2, ',', ' '); ?> €</td>
                                    <td><?php
                                    switch ($fiche['idEtat']) {
                                        case 'CR': echo 'En attente'; break;
                                        case 'VA': echo 'Validée'; break;
                                        case 'RB': echo 'Refusée / Remboursée'; break;
                                        case 'CL': echo 'Clôturée'; break;
                                        default: echo htmlspecialchars($fiche['idEtat']);
                                    }
                                    ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align:center;">Aucun frais hors forfait</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align:center; color:#666;">Aucune note de frais enregistrée pour le moment.</p>
            <?php endif; ?>
        </div>


    </div>
    <script type="text/javascript" src="<?php echo base_url('/public/Js/expence.js'); ?>"> </script>
</body>

</html>