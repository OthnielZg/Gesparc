<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bordereau d'Affectation - {{ $affectation->reference_bordereau }}</title>
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica', sans-serif; color: #1a1a1a; line-height: 1.6; font-size: 11px; margin: 0; padding: 0; }
        .top-stripe { height: 12px; background: #009E60; width: 100%; }
        .amber-stripe { height: 6px; background: #F77F00; width: 100%; }
        .content { padding: 40px; }
        
        .header { margin-bottom: 40px; position: relative; }
        .logo-box { float: left; }
        .logo { font-size: 28px; font-weight: 900; color: #1a1a1a; letter-spacing: -1.5px; }
        .logo span { color: #F77F00; }
        .sub-logo { font-size: 9px; font-weight: 800; color: #a1a1a1; text-transform: uppercase; letter-spacing: 2px; margin-top: -5px; }
        
        .qr-box { float: right; text-align: right; }
        .qr-code { width: 85px; height: 85px; border: 1px solid #f0f0f0; padding: 5px; border-radius: 8px; }
        .qr-label { font-size: 7px; font-weight: 900; color: #009E60; text-transform: uppercase; margin-top: 4px; letter-spacing: 1px; }

        .title-container { clear: both; padding-top: 30px; margin-bottom: 40px; }
        .doc-title { font-size: 22px; font-weight: 900; color: #1a1a1a; text-transform: uppercase; letter-spacing: -0.5px; border-left: 6px solid #009E60; padding-left: 20px; }
        .doc-ref { font-size: 11px; font-weight: 700; color: #F77F00; margin-top: 5px; padding-left: 26px; }

        .grid { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .grid th { text-align: left; padding: 12px 0; border-bottom: 1px solid #f0f0f0; color: #a1a1a1; font-size: 9px; text-transform: uppercase; font-weight: 900; letter-spacing: 1px; }
        .grid td { padding: 15px 0; border-bottom: 1px solid #f0f0f0; font-size: 12px; font-weight: 700; color: #1a1a1a; }
        
        .item-card { background: #fafafa; border-radius: 20px; padding: 25px; margin-bottom: 30px; border: 1px solid #f0f0f0; }
        .card-header { font-size: 9px; font-weight: 900; color: #009E60; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 15px; }
        
        .footer { margin-top: 60px; }
        .signature-table { width: 100%; margin-top: 20px; }
        .signature-box { border: 2px dashed #f0f0f0; border-radius: 20px; padding: 25px; height: 130px; vertical-align: top; }
        .signature-label { font-size: 10px; font-weight: 900; color: #1a1a1a; text-transform: uppercase; display: block; margin-bottom: 5px; }
        .signature-hint { font-size: 8px; color: #a1a1a1; font-weight: 600; font-style: italic; }

        .stamp { position: absolute; top: 50%; left: 50%; opacity: 0.03; transform: translate(-50%, -50%) rotate(-15deg); font-size: 120px; font-weight: 900; z-index: -10; width: 100%; text-align: center; }
    </style>
</head>
<body>
    <div class="top-stripe"></div>
    <div class="amber-stripe"></div>

    <div class="stamp">DGI</div>

    <div class="content">
        <div class="header">
            <div class="logo-box">
                <div class="logo">GES<span>PARC</span></div>
                <div class="sub-logo">Parc Informatique DGI</div>
            </div>
            
            <div class="qr-box">
                @php
                    $qrPayload = $affectation->materiel->numero_serie . ' - ' . $affectation->utilisateur->full_name;
                    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrPayload);
                @endphp
                <img src="{{ $qrUrl }}" class="qr-code">
                <div class="qr-label">Traçabilité Sécurisée</div>
            </div>
        </div>

        <div class="title-container">
            <div class="doc-title">Bordereau d'Affectation</div>
            <div class="doc-ref">Ref: {{ $affectation->reference_bordereau }}</div>
        </div>

        <div class="info-section">
            <table class="grid">
                <tr>
                    <th width="50%">Bénéficiaire du matériel</th>
                    <th width="50%">Date d'affectation</th>
                </tr>
                <tr>
                    <td>{{ $affectation->utilisateur->full_name }}</td>
                    <td>{{ $affectation->date_affectation->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Localisation / Bureau</th>
                    <th>Direction de rattachement</th>
                </tr>
                <tr>
                    <td>{{ $affectation->utilisateur->bureau?->nom_bureau ?? '—' }}</td>
                    <td>Direction Générale des Impôts</td>
                </tr>
            </table>
        </div>

        <div class="item-card">
            <div class="card-header">Détails de l'Unité Affectée</div>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="border: none; padding: 0;">
                        <span style="display: block; font-size: 8px; font-weight: 900; color: #a1a1a1; text-transform: uppercase;">Désignation</span>
                        <span style="font-size: 14px; color: #1a1a1a;">{{ $affectation->materiel->marque }} {{ $affectation->materiel->modele }}</span>
                    </td>
                    <td style="border: none; padding: 0; text-align: right;">
                        <span style="display: block; font-size: 8px; font-weight: 900; color: #a1a1a1; text-transform: uppercase;">Numéro de Série</span>
                        <span style="font-size: 14px; color: #F77F00; font-family: 'Courier', monospace;">{{ $affectation->materiel->numero_serie }}</span>
                    </td>
                </tr>
            </table>
            
            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; overflow: hidden;">
                <div style="float: left; width: 45%;">
                    <span style="display: block; font-size: 8px; font-weight: 900; color: #a1a1a1; text-transform: uppercase;">Catégorie</span>
                    <span style="font-size: 10px; font-weight: 800; color: #1a1a1a; text-transform: uppercase;">{{ $affectation->materiel->categorie }}</span>
                </div>
                <div style="float: right; width: 45%; text-align: right;">
                    <span style="display: block; font-size: 8px; font-weight: 900; color: #a1a1a1; text-transform: uppercase;">État à la mise en service</span>
                    <span style="font-size: 10px; font-weight: 800; color: #1a1a1a; text-transform: uppercase;">{{ $affectation->etat_reception }}</span>
                </div>
            </div>
        </div>

        <div style="background: #fff; padding: 20px; border-left: 3px solid #F77F00; font-size: 9px; color: #666; font-style: italic;">
            <strong>Mentions Légales :</strong> Le matériel ci-dessus reste la propriété exclusive de la DGI. L'utilisateur en est le gardien et responsable de son intégrité physique et logicielle. Toute anomalie doit faire l'objet d'un signalement immédiat sur GESPARC.
        </div>

        <div class="footer">
            <table class="signature-table" style="border-spacing: 20px 0; margin: 0 -20px;">
                <tr>
                    <td class="signature-box" width="50%">
                        <span class="signature-label">Le Bénéficiaire</span>
                        <span class="signature-hint">(Signature précédée de "Lu et approuvé")</span>
                    </td>
                    <td class="signature-box" width="50%">
                        <span class="signature-label">La Direction / DSI</span>
                        <span class="signature-hint">Scellé numérique Gesparc — {{ $date }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 40px; text-align: center; border-top: 1px solid #f0f0f0; padding-top: 15px;">
            <p style="font-size: 7px; color: #a1a1a1; text-transform: uppercase; letter-spacing: 1px;">
                Ceci est un document officiel généré par le système GESPARC - DGI Côte d'Ivoire. <br>
                L'intégrité de ce document est vérifiable via le QR Code ci-dessus.
            </p>
        </div>
    </div>
</body>
</html>
