<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bordereau d'Affectation - {{ $affectation->reference_bordereau }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #009E60; padding-bottom: 10px; }
        .logo { font-size: 24px; font-weight: bold; color: #333; }
        .logo span { color: #F77F00; }
        .title { text-align: center; background: #f4f4f4; padding: 10px; font-weight: bold; margin-bottom: 20px; font-size: 16px; text-transform: uppercase; }
        .info-section { margin-bottom: 25px; }
        .section-title { font-weight: bold; text-decoration: underline; margin-bottom: 10px; text-transform: uppercase; display: block; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f9f9f9; font-weight: bold; }
        .footer { margin-top: 50px; }
        .signature-grid { width: 100%; }
        .signature-grid td { border: none; width: 50%; vertical-align: top; height: 120px; }
        .qr-code { float: right; margin-top: -10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">GES<span>PARC</span></div>
        <div style="font-size: 10px; text-transform: uppercase; font-weight: bold; color: #666;">Direction Générale des Impôts - République de Côte d'Ivoire</div>
    </div>

    <div class="title">Bordereau d'Affectation de Matériel</div>

    <div class="info-section">
        <span class="section-title">Informations de la Demande</span>
        <table>
            <tr>
                <th width="30%">Référence :</th>
                <td>{{ $affectation->reference_bordereau }}</td>
            </tr>
            <tr>
                <th>Date d'Affectation :</th>
                <td>{{ $affectation->date_affectation->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Bénéficiaire :</th>
                <td><strong>{{ $affectation->utilisateur->full_name }}</strong></td>
            </tr>
            <tr>
                <th>Service / Bureau :</th>
                <td>{{ $affectation->utilisateur->bureau?->nom_bureau ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <span class="section-title">Détails du Matériel</span>
        <table>
            <thead>
                <tr>
                    <th>Catégorie</th>
                    <th>Marque & Modèle</th>
                    <th>Numéro de Série</th>
                    <th>État à la réception</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $affectation->materiel->categorie }}</td>
                    <td>{{ $affectation->materiel->marque }} {{ $affectation->materiel->modele }}</td>
                    <td><code>{{ $affectation->materiel->numero_serie }}</code></td>
                    <td>{{ ucfirst($affectation->etat_reception) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="info-section">
        <span class="section-title">Conditions d'Utilisation</span>
        <p style="font-size: 10px; color: #555;">
            Le matériel listé ci-dessus est mis à la disposition du bénéficiaire pour l'exercice de ses fonctions. 
            Le bénéficiaire s'engage à en faire un usage professionnel et à signaler tout dysfonctionnement à la DSI.
            En cas de départ du service, le matériel doit être restitué en bon état.
        </p>
    </div>

    <div class="footer">
        <table class="signature-grid">
            <tr>
                <td>
                    <strong>Le Bénéficiaire</strong><br>
                    <span style="font-size: 9px; color: #999;">(Signature précédée de "Lu et approuvé")</span>
                </td>
                <td style="text-align: right;">
                    <strong>Pour la DSI / GESPARC</strong><br>
                    <span style="font-size: 9px; color: #999;">Fait à Abidjan, le {{ $date }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div style="position: fixed; bottom: 0; width: 100%; border-top: 1px solid #eee; padding-top: 5px; font-size: 8px; color: #999; text-align: center;">
        Document généré automatiquement par GESPARC - Portail de Gestion du Parc Informatique DGI
    </div>
</body>
</html>
